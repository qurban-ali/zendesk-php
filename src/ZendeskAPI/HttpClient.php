<?php

namespace Qurban\ZendeskAPI;

/*
 * Dead simple autoloader:
 * spl_autoload_register(function($c){@include 'src/'.preg_replace('#\\\|_(?!.+\\\)#','/',$c).'.php';});
 */

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use Qurban\ZendeskAPI\Exceptions\AuthException;
use Qurban\ZendeskAPI\Middleware\RetryHandler;
use Qurban\ZendeskAPI\Resources\Chat;
use Qurban\ZendeskAPI\Resources\Core\Activities;
use Qurban\ZendeskAPI\Resources\Core\AppInstallations;
use Qurban\ZendeskAPI\Resources\Core\Apps;
use Qurban\ZendeskAPI\Resources\Core\Attachments;
use Qurban\ZendeskAPI\Resources\Core\AuditLogs;
use Qurban\ZendeskAPI\Resources\Core\Autocomplete;
use Qurban\ZendeskAPI\Resources\Core\Automations;
use Qurban\ZendeskAPI\Resources\Core\Bookmarks;
use Qurban\ZendeskAPI\Resources\Core\Brands;
use Qurban\ZendeskAPI\Resources\Core\CustomRoles;
use Qurban\ZendeskAPI\Resources\Core\DynamicContent;
use Qurban\ZendeskAPI\Resources\Core\GroupMemberships;
use Qurban\ZendeskAPI\Resources\Core\Groups;
use Qurban\ZendeskAPI\Resources\Core\Incremental;
use Qurban\ZendeskAPI\Resources\Core\JobStatuses;
use Qurban\ZendeskAPI\Resources\Core\Locales;
use Qurban\ZendeskAPI\Resources\Core\Macros;
use Qurban\ZendeskAPI\Resources\Core\OAuthClients;
use Qurban\ZendeskAPI\Resources\Core\OAuthTokens;
use Qurban\ZendeskAPI\Resources\Core\OrganizationFields;
use Qurban\ZendeskAPI\Resources\Core\OrganizationMemberships;
use Qurban\ZendeskAPI\Resources\Core\Organizations;
use Qurban\ZendeskAPI\Resources\Core\OrganizationSubscriptions;
use Qurban\ZendeskAPI\Resources\Core\PushNotificationDevices;
use Qurban\ZendeskAPI\Resources\Core\Requests;
use Qurban\ZendeskAPI\Resources\Core\SatisfactionRatings;
use Qurban\ZendeskAPI\Resources\Core\Search;
use Qurban\ZendeskAPI\Resources\Core\Sessions;
use Qurban\ZendeskAPI\Resources\Core\SharingAgreements;
use Qurban\ZendeskAPI\Resources\Core\SlaPolicies;
use Qurban\ZendeskAPI\Resources\Core\SupportAddresses;
use Qurban\ZendeskAPI\Resources\Core\SuspendedTickets;
use Qurban\ZendeskAPI\Resources\Core\Tags;
use Qurban\ZendeskAPI\Resources\Core\Targets;
use Qurban\ZendeskAPI\Resources\Core\TicketFields;
use Qurban\ZendeskAPI\Resources\Core\TicketImports;
use Qurban\ZendeskAPI\Resources\Core\Tickets;
use Qurban\ZendeskAPI\Resources\Core\Translations;
use Qurban\ZendeskAPI\Resources\Core\Triggers;
use Qurban\ZendeskAPI\Resources\Core\TwitterHandles;
use Qurban\ZendeskAPI\Resources\Core\UserFields;
use Qurban\ZendeskAPI\Resources\Core\Users;
use Qurban\ZendeskAPI\Resources\Core\Views;
use Qurban\ZendeskAPI\Resources\Core\Webhooks;
use Qurban\ZendeskAPI\Resources\Embeddable;
use Qurban\ZendeskAPI\Resources\HelpCenter;
use Qurban\ZendeskAPI\Resources\Talk;
use Qurban\ZendeskAPI\Resources\Voice;
use Qurban\ZendeskAPI\Resources\CustomData;
use Qurban\ZendeskAPI\Traits\Utility\InstantiateTrait;
use Qurban\ZendeskAPI\Utilities\Auth;

/**
 * Client class, base level access
 *
 * @method Activities activities()
 * @method Apps apps()
 * @method AppInstallations appInstallations()
 * @method Attachments attachments($id = null)
 * @method AuditLogs auditLogs()
 * @method AutoComplete autocomplete()
 * @method Automations automations()
 * @method Bookmarks bookmarks()
 * @method Brands brands($id = null)
 * @method CustomRoles customRoles()
 * @method DynamicContent dynamicContent()
 * @method GroupMemberships groupMemberships()
 * @method Groups groups($id = null)
 * @method Incremental incremental()
 * @method JobStatuses jobStatuses()
 * @method Locales locales()
 * @method Macros macros()
 * @method OAuthClients oauthClients($id = null)
 * @method OAuthTokens oauthTokens($id = null)
 * @method OrganizationFields organizationFields()
 * @method OrganizationMemberships organizationMemberships()
 * @method Organizations organizations($id = null)
 * @method OrganizationSubscriptions organizationSubscriptions()
 * @method PushNotificationDevices pushNotificationDevices()
 * @method Requests requests($id = null)
 * @method SatisfactionRatings satisfactionRatings()
 * @method Search search()
 * @method Sessions sessions($id = null)
 * @method SharingAgreements sharingAgreements()
 * @method SlaPolicies slaPolicies()
 * @method SupportAddresses supportAddresses()
 * @method SuspendedTickets suspendedTickets()
 * @method Tags tags($id = null)
 * @method Targets targets()
 * @method Webhooks webhooks()
 * @method Tickets tickets($id = null)
 * @method TicketFields ticketFields($id = null)
 * @method TicketImports ticketImports()
 * @method Triggers triggers()
 * @method TwitterHandles twitterHandles()
 * @method UserFields userFields($id = null)
 * @method Users users($id = null)
 * @method Views views()
 *
 */
class HttpClient
{

    const VERSION = '1.1.3';

    use InstantiateTrait;

    /**
     * @var array $headers
     */
    private $headers = [];

    /**
     * @var Auth
     */
    protected $auth;
    /**
     * @var string
     */
    protected $subdomain;
    /**
     * @var string
     */
    protected $scheme;
    /**
     * @var string
     */
    protected $hostname;
    /**
     * @var integer
     */
    protected $port;
    /**
     * @var string
     */
    protected $apiUrl;
    /**
     * @var string This is appended between the full base domain and the resource endpoint
     */
    protected $apiBasePath;
    /**
     * @var array|null
     */
    protected $sideload;
    /**
     * @var Debug
     */
    protected $debug;

    /**
     * Whether or not to print every ZendeskAPI call details right before execution
     *
     * E.G.: Qurban\ZendeskAPI\Http: GET https://my_company.zendesk.com/api/v2/tickets.json
     *
     * @var boolean
     */
    public $log_api_calls = false;

    /**
     * @var \GuzzleHttp\Client
     */
    public $guzzle;

    /**
     * @var HelpCenter
     */
    public $helpCenter;

    /**
     * @var Voice
     */
    public $voice;

    /**
     * @var CustomData
     */
    public $customData;

    /**
     * @var Embeddable
     */
    public $embeddable;

    /**
     * @var Chat
     */
    public $chat;

    /**
     * @var Talk
     */
    public $talk;

    /**
     * @param string             $subdomain
     * @param string             $username
     * @param string             $scheme
     * @param string             $hostname
     * @param int                $port
     * @param \GuzzleHttp\Client $guzzle
     */

    public function __construct($subdomain, $username = '', $scheme = "https", $hostname = "zendesk.com", $port = 443, $guzzle = null)
    {
        if (is_null($guzzle)) {
            $handler = HandlerStack::create();
            $handler->push(new RetryHandler([
                'retry_if' => function ($retries, $request, $response, $e) {
                    return $e instanceof RequestException && strpos($e->getMessage(), 'ssl') !== false;
                }
            ]), 'retry_handler');
            $this->guzzle = new \GuzzleHttp\Client(compact('handler'));
        } else {
            $this->guzzle = $guzzle;
        }

        $this->subdomain = $subdomain;
        $this->hostname = $hostname;
        $this->scheme = $scheme;
        $this->port = $port;

        if (empty($subdomain)) {
            $this->apiUrl = "$scheme://$hostname:$port/";
        } else {
            $this->apiUrl = "$scheme://$subdomain.$hostname:$port/";
        }

        $this->debug = new Debug();
        $this->helpCenter = new HelpCenter($this);
        $this->voice = new Voice($this);
        $this->customData = new CustomData($this);
        $this->embeddable = new Embeddable($this);
        $this->chat = new Chat($this);
        $this->talk = new Talk($this);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getValidSubResources()
    {
        return [
            'apps'                      => Apps::class,
            'activities'                => Activities::class,
            'appInstallations'          => AppInstallations::class,
            'attachments'               => Attachments::class,
            'auditLogs'                 => AuditLogs::class,
            'autocomplete'              => Autocomplete::class,
            'automations'               => Automations::class,
            'bookmarks'                 => Bookmarks::class,
            'brands'                    => Brands::class,
            'customRoles'               => CustomRoles::class,
            'dynamicContent'            => DynamicContent::class,
            'groupMemberships'          => GroupMemberships::class,
            'groups'                    => Groups::class,
            'incremental'               => Incremental::class,
            'jobStatuses'               => JobStatuses::class,
            'locales'                   => Locales::class,
            'macros'                    => Macros::class,
            'oauthClients'              => OAuthClients::class,
            'oauthTokens'               => OAuthTokens::class,
            'organizationFields'        => OrganizationFields::class,
            'organizationMemberships'   => OrganizationMemberships::class,
            'organizations'             => Organizations::class,
            'organizationSubscriptions' => OrganizationSubscriptions::class,
            'pushNotificationDevices'   => PushNotificationDevices::class,
            'requests'                  => Requests::class,
            'satisfactionRatings'       => SatisfactionRatings::class,
            'sharingAgreements'         => SharingAgreements::class,
            'search'                    => Search::class,
            'slaPolicies'               => SlaPolicies::class,
            'sessions'                  => Sessions::class,
            'supportAddresses'          => SupportAddresses::class,
            'suspendedTickets'          => SuspendedTickets::class,
            'tags'                      => Tags::class,
            'targets'                   => Targets::class,
            'tickets'                   => Tickets::class,
            'ticketFields'              => TicketFields::class,
            'ticketImports'             => TicketImports::class,
            'translations'              => Translations::class,
            'triggers'                  => Triggers::class,
            'twitterHandles'            => TwitterHandles::class,
            'userFields'                => UserFields::class,
            'users'                     => Users::class,
            'views'                     => Views::class,
            'webhooks'                  => Webhooks::class,
        ];
    }

    /**
     * @return Auth
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Configure the authorization method
     *
     * @param       $strategy
     * @param array $options
     *
     * @throws AuthException
     */
    public function setAuth($strategy, array $options)
    {
        $this->auth = new Auth($strategy, $options);
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $key   The name of the header to set
     * @param string $value The value to set in the header
     *
     * @return HttpClient
     * @internal param array $headers
     *
     */
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Return the user agent string
     *
     * @return string
     */
    public function getUserAgent()
    {
        return 'ZendeskAPI PHP ' . self::VERSION;
    }

    /**
     * Returns the supplied subdomain
     *
     * @return string
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * Returns the generated api URL
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Sets the api base path
     *
     * @param string $apiBasePath
     */
    public function setApiBasePath($apiBasePath)
    {
        $this->apiBasePath = $apiBasePath;
    }

    /**
     * Returns the api base path
     *
     * @return string
     */
    public function getApiBasePath()
    {
        return $this->apiBasePath;
    }

    /**
     * Set debug information as an object
     *
     * @param mixed  $lastRequestHeaders
     * @param mixed  $lastRequestBody
     * @param mixed  $lastResponseCode
     * @param string $lastResponseHeaders
     * @param mixed  $lastResponseError
     */
    public function setDebug($lastRequestHeaders, $lastRequestBody, $lastResponseCode, $lastResponseHeaders, $lastResponseError)
    {
        $this->debug->lastRequestHeaders = $lastRequestHeaders;
        $this->debug->lastRequestBody = $lastRequestBody;
        $this->debug->lastResponseCode = $lastResponseCode;
        $this->debug->lastResponseHeaders = $lastResponseHeaders;
        $this->debug->lastResponseError = $lastResponseError;
    }

    /**
     * Returns debug information in an object
     *
     * @return Debug
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Sideload setter
     *
     * @param array|null $fields
     *
     * @return HttpClient
     */
    public function setSideload(array $fields = null)
    {
        $this->sideload = $fields;

        return $this;
    }

    /**
     * Sideload getter
     *
     * @param array|null $params
     *
     * @return array|null
     */
    public function getSideload(array $params = [])
    {
        // Allow both for backward compatibility
        $sideloadKeys = [
            'include',
            'sideload'
        ];

        if (! empty($sideloads = array_intersect_key($params, array_flip($sideloadKeys)))) {
            // Merge to a single array
            return call_user_func_array('array_merge', array_values($sideloads));
        } else {
            return $this->sideload;
        }
    }

    /**
     * This is a helper method to do a get request.
     *
     * @param       $endpoint
     * @param array $queryParams
     *
     * @return \stdClass | null
     * @throws \Qurban\ZendeskAPI\Exceptions\AuthException
     * @throws \Qurban\ZendeskAPI\Exceptions\ApiResponseException
     */
    public function get($endpoint, $queryParams = [])
    {
        $sideloads = $this->getSideload($queryParams);

        if (is_array($sideloads)) {
            $queryParams['include'] = implode(',', $sideloads);
            unset($queryParams['sideload']);
        }

        $response = Http::send($this, $endpoint, ['queryParams' => $queryParams]);

        return $response;
    }

    /**
     * This is a helper method to do a post request.
     *
     * @param       $endpoint
     * @param array $postData
     *
     * @param array $options
     *
     * @return null|\stdClass
     * @throws \Qurban\ZendeskAPI\Exceptions\AuthException
     * @throws \Qurban\ZendeskAPI\Exceptions\ApiResponseException
     */
    public function post($endpoint, $postData = [], $options = [])
    {
        $extraOptions = array_merge($options, [
            'postFields' => $postData,
            'method'     => 'POST'
        ]);

        $response = Http::send($this, $endpoint, $extraOptions);

        return $response;
    }

    /**
     * This is a helper method to do a put request.
     *
     * @param       $endpoint
     * @param array $putData
     *
     * @return \stdClass | null
     * @throws \Qurban\ZendeskAPI\Exceptions\AuthException
     * @throws \Qurban\ZendeskAPI\Exceptions\ApiResponseException
     */
    public function put($endpoint, $putData = [])
    {
        $response = Http::send($this, $endpoint, [
                'postFields' => $putData,
                'method'     => 'PUT'
            ]);

        return $response;
    }

    /**
     * This is a helper method to do a delete request.
     *
     * @param $endpoint
     *
     * @return null
     * @throws \Qurban\ZendeskAPI\Exceptions\AuthException
     * @throws \Qurban\ZendeskAPI\Exceptions\ApiResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete($endpoint)
    {
        return Http::send($this, $endpoint, ['method' => 'DELETE']);
    }
}
