<?php

namespace Qurban\ZendeskAPI\Resources;

use Qurban\ZendeskAPI\HttpClient;
use Qurban\ZendeskAPI\Resources\Chat\Apps;
use Qurban\ZendeskAPI\Resources\Chat\Integrations;
use Qurban\ZendeskAPI\Traits\Utility\ChainedParametersTrait;
use Qurban\ZendeskAPI\Traits\Utility\InstantiatorTrait;

/**
 * This class serves as a container to allow calls to $this->client->chat
 *
 * @method Apps apps()
 */
class Chat
{
    use ChainedParametersTrait;
    use InstantiatorTrait;

    public $client;

    /**
     * Sets the client to be used
     *
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     */
    public static function getValidSubResources()
    {
        return [
            'apps' => Apps::class,
            'integrations' => Integrations::class,
        ];
    }
}
