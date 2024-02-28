<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Exceptions\MissingParametersException;
use Qurban\ZendeskAPI\Exceptions\ResponseException;
use Qurban\ZendeskAPI\Http;
use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Resource\CreateMany;
use Qurban\ZendeskAPI\Traits\Resource\Defaults;
use Qurban\ZendeskAPI\Traits\Resource\DeleteMany;
use Qurban\ZendeskAPI\Traits\Resource\FindMany;
use Qurban\ZendeskAPI\Traits\Resource\UpdateMany;
use Qurban\ZendeskAPI\Traits\Utility\InstantiateTrait;

/**
 * The Tickets class exposes key methods for reading and updating ticket data
 * @method TicketComments comments()
 * @method TicketForms forms()
 * @method Tags tags()
 * @method TicketAudits audits()
 * @method Attachments attachments()
 * @method TicketMetrics metrics()
 */
class Tickets extends ResourceAbstract
{

    use InstantiateTrait;

    use Defaults {
        create as traitCreate;
        update as traitUpdate;
    }

    use CreateMany;

    use FindMany;
    use UpdateMany {
        UpdateMany::updateMany as bulkUpdate;
    }
    use DeleteMany;

    /**
     * @var array
     */
    protected array $lastAttachments = [];

    /**
     * {@inheritdoc}
     */
    public static function getValidSubResources(): array
    {
        return [
            'comments'            => TicketComments::class,
            'forms'               => TicketForms::class,
            'tags'                => Tags::class,
            'audits'              => TicketAudits::class,
            'attachments'         => Attachments::class,
            'metrics'             => TicketMetrics::class,
            'satisfactionRatings' => SatisfactionRatings::class,
        ];
    }

    /**
     * Wrapper for common GET requests
     *
     * @param       $route
     * @param array $params
     *
     * @return \stdClass | null
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    private function sendGetRequest($route, array $params = []): ?\stdClass
    {
        return Http::send($this->client, $this->getRoute($route, $params), ['queryParams' => $params]);
    }

    /**
     * Declares routes to be used by this resource.
     */
    protected function setUpRoutes()
    {
        parent::setUpRoutes();

        $this->setRoutes([
            'create'              => 'tickets.json',
            'createMany'          => 'tickets/create_many.json',
            'findMany'            => 'tickets/show_many.json',
            'updateMany'          => 'tickets/update_many.json',
            'markAsSpam'          => 'tickets/{id}/mark_as_spam.json',
            'markManyAsSpam'      => 'tickets/mark_many_as_spam.json',
            'related'             => 'tickets/{id}/related.json',
            'deleteMany'          => 'tickets/destroy_many.json',
            'collaborators'       => 'tickets/{id}/collaborators.json',
            'incidents'           => 'tickets/{id}/incidents.json',
            'merge'               => 'tickets/{id}/merge.json',
            'problems'            => 'problems.json',
            'export'              => 'exports/tickets.json',
            'problemAutoComplete' => 'problems/autocomplete.json'
        ]);
    }

    /**
     * Find a specific twitter generated ticket by id
     *
     * @param array $params
     *
     * @return \stdClass | null
     * @throws ResponseException
     * @throws \Exception
     *
     * @throws MissingParametersException|\GuzzleHttp\Exception\GuzzleException
     */
    /*public function findTwicket(array $params = []): ?\stdClass
    {
        $params = $this->addChainedParametersToParams($params, ['id' => get_class($this)]);

        if (! $this->hasKeys($params, ['id'])) {
            throw new MissingParametersException(__METHOD__, ['id']);
        }
        $endPointBase = 'channels/twitter/tickets/' . $params['id'] . '/statuses.json';
        $endPoint = Http::prepare($endPointBase . (is_array($params['comment_ids']) ? '?' . implode(',', $params['comment_ids']) : ''), $this->client->getSideload($params));

        return Http::send($this->client, $endPoint);
    }*/

    /**
     * Create a ticket
     *
     * @param array $params
     *
     * @return \stdClass | null
     * @throws \Exception
     * @throws \Qurban\ZendeskAPI\Exceptions\AuthException
     * @throws \Qurban\ZendeskAPI\Exceptions\ApiResponseException
     */
    public function create(array $params): ?\stdClass
    {
        if (count($this->lastAttachments)) {
            $params['comment']['uploads'] = $this->lastAttachments;
            $this->lastAttachments = [];
        }

        $extraOptions = [];
        if (isset($params['async']) && ($params['async'] == true)) {
            $extraOptions = [
                'queryParams' => [
                    'async' => true
                ]
            ];
        }

        $route = $this->getRoute(__FUNCTION__, $params);

        return $this->client->post($route, [$this->objectName => $params], $extraOptions);
    }

    /**
     * Create a ticket from a tweet
     *
     * @param array $params
     *
     * @return \stdClass | null
     * @throws ResponseException
     * @throws \Exception
     * @throws MissingParametersException|\GuzzleHttp\Exception\GuzzleException
     */
    /*public function createFromTweet(array $params): ?\stdClass
    {
        if ((! $params['twitter_status_message_id']) || (! $params['monitored_twitter_handle_id'])) {
            throw new MissingParametersException(__METHOD__, [
                    'twitter_status_message_id',
                    'monitored_twitter_handle_id'
                ]);
        }
        $endPoint = Http::prepare('channels/twitter/tickets.json');
        $response = Http::send($this->client, $endPoint, [self::OBJ_NAME => $params], 'POST');
        $lastResponseCode = $this->client->getDebug()->lastResponseCode;
        if ((! is_object($response)) || ($lastResponseCode != 201)) {
            throw new ResponseException(__METHOD__, ($lastResponseCode == 422 ? ' (hint: you can\'t create two tickets from the same tweet)' : ''));
        }

        return $response;
    }*/

    /**
     * Update a ticket or series of tickets
     *
     * @param int   $id
     * @param array $updateResourceFields
     *
     * @return null|\stdClass
     */
    public function update($id = null, array $updateResourceFields = []): ?\stdClass
    {
        if (count($this->lastAttachments)) {
            $updateResourceFields['comment']['uploads'] = $this->lastAttachments;
            $this->lastAttachments = [];
        }

        return $this->traitUpdate($id, $updateResourceFields);
    }

    /**
     * Update a ticket or series of tickets
     *
     * @param array $params
     *
     * @return \stdClass | null
     * @throws \Exception
     */
    public function updateMany(array $params): ?\stdClass
    {
        if (count($this->lastAttachments)) {
            $params['comment']['uploads'] = $this->lastAttachments;
            $this->lastAttachments = [];
        }

        return $this->bulkUpdate($params);
    }

    /**
     * Mark a ticket as spam and suspend the requester
     *
     * @param mixed $id The ticket ID, or an array of ticket ID's to mark as spam
     *
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     * @return \stdClass | null
     */
    public function markAsSpam($id = null): ?\stdClass
    {
        $options = ['method' => 'PUT'];

        if (is_array($id)) {
            $options['queryParams']['ids'] = implode(',', $id);
            $route = $this->getRoute('markManyAsSpam');
        } else {
            $params = $this->addChainedParametersToParams(['id' => $id], ['id' => get_class($this)]);
            $route = $this->getRoute('markAsSpam', $params);
        }

        $response = Http::send($this->client, $route, $options);

        return $response;
    }

    /**
     * Get related ticket information
     *
     * @param array $params
     *
     * @return \stdClass | null
     * @throws ResponseException
     * @throws \Exception
     * @throws MissingParametersException
     */
    public function related(array $params = [])
    {
        $params = $this->addChainedParametersToParams($params, ['id' => get_class($this)]);

        if (! $this->hasKeys($params, ['id'])) {
            throw new MissingParametersException(__METHOD__, ['id']);
        }

        return $this->sendGetRequest(__FUNCTION__, $params);
    }

    /**
     * List collaborators for a ticket
     *
     * @param array $params
     *
     * @return \stdClass | null
     * @throws ResponseException
     * @throws \Exception
     * @throws MissingParametersException
     */
    public function collaborators(array $params = [])
    {
        $params = $this->addChainedParametersToParams($params, ['id' => get_class($this)]);

        if (! $this->hasKeys($params, ['id'])) {
            throw new MissingParametersException(__METHOD__, ['id']);
        }

        return $this->sendGetRequest(__FUNCTION__, $params);
    }

    /**
     * List incidents for a ticket
     *
     * @param array $params
     *
     * @return \stdClass | null
     * @throws ResponseException
     * @throws \Exception
     * @throws MissingParametersException
     */
    public function incidents(array $params = [])
    {
        $params = $this->addChainedParametersToParams($params, ['id' => get_class($this)]);

        if (! $this->hasKeys($params, ['id'])) {
            throw new MissingParametersException(__METHOD__, ['id']);
        }

        return $this->sendGetRequest(__FUNCTION__, $params);
    }

    /**
     * List all problem tickets
     *
     * @param array $params
     *
     * @return \stdClass | null
     * @throws ResponseException
     * @throws \Exception
     * @throws MissingParametersException
     */
    public function problems(array $params = [])
    {
        return $this->sendGetRequest(__FUNCTION__, $params);
    }

    /**
     * Add a problem autocomplete
     *
     * @param array $params
     *
     * @return \stdClass | null
     * @throws ResponseException
     * @throws \Exception
     * @throws MissingParametersException
     */
    public function problemAutoComplete(array $params)
    {
        if (! $params['text']) {
            throw new MissingParametersException(__METHOD__, ['text']);
        }

        $response = Http::send($this->client, $this->getRoute('problemAutoComplete'), [
                'method'     => 'POST',
                'postFields' => ['text' => $params['text']]
            ]);

        return $response;
    }

    /**
     * Incremental ticket exports with a supplied start_time
     *
     * @param array $params
     *
     * @return \stdClass | null
     * @throws ResponseException
     * @throws \Exception
     * @throws MissingParametersException
     */
    public function export(array $params)
    {
        if (! $params['start_time']) {
            throw new MissingParametersException(__METHOD__, ['start_time']);
        }

        $queryParams = ["start_time" => $params["start_time"]];

        $response = Http::send($this->client, $this->getRoute('export'), ['queryParams' => $queryParams]);

        return $response;
    }

    /**
     * @param array $params
     *
     * @return Tickets
     * @throws ResponseException
     * @throws MissingParametersException
     */
    public function attach(array $params = [])
    {
        if (! $this->hasKeys($params, ['file'])) {
            throw new MissingParametersException(__METHOD__, ['file']);
        }

        $upload = $this->client->attachments()
            ->upload($params);

        if ((! is_object($upload->upload)) || (! $upload->upload->token)) {
            throw new ResponseException(__METHOD__);
        }
        $this->lastAttachments[] = $upload->upload->token;

        return $this;
    }

    /**
     * @param array $params
     *
     * @return \stdClass | null
     * @throws ResponseException
     * @throws MissingParametersException
     */
    public function merge(array $params = [])
    {
        $params = $this->addChainedParametersToParams($params, ['id' => get_class($this)]);

        if (! $this->hasKeys($params, [
            'id',
            'ids'
        ])) {
            throw new MissingParametersException(__METHOD__, [
                'id',
                'ids'
            ]);
        }

        $route = $this->getRoute(__FUNCTION__, ['id' => $params['id']]);
        unset($params['id']);

        $response = Http::send($this->client, $route, [
                'method'     => 'POST',
                'postFields' => $params,
            ]);

        return $response;
    }
}
