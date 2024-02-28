<?php

namespace Qurban\ZendeskAPI\Resources;

use Qurban\ZendeskAPI\HttpClient;
use Qurban\ZendeskAPI\Resources\Embeddable\ConfigSets;
use Qurban\ZendeskAPI\Traits\Utility\ChainedParametersTrait;
use Qurban\ZendeskAPI\Traits\Utility\InstantiateTrait;

/**
 * This class serves as a container to allow $this->client->embeddable
 *
 * @method ConfigSets configSets()
 */
class Embeddable
{
    use ChainedParametersTrait;
    use InstantiateTrait;

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
     * @inheritdoc
     * @return array
     */
    public static function getValidSubResources()
    {
        return [
            'configSets' => ConfigSets::class,
        ];
    }
}
