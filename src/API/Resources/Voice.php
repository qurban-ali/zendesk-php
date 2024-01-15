<?php

namespace Qurban\ZendeskAPI\Resources;

use Qurban\ZendeskAPI\HttpClient;
use Qurban\ZendeskAPI\Resources\Voice\PhoneNumbers;
use Qurban\ZendeskAPI\Traits\Utility\ChainedParametersTrait;
use Qurban\ZendeskAPI\Traits\Utility\InstantiatorTrait;

/**
 * This class serves as a container to allow $this->client->helpCenter
 *
 * @method PhoneNumbers phoneNumbers()
 */
class Voice
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
     * @inheritdoc
     * @return array
     */
    public static function getValidSubResources()
    {
        return [
            'phoneNumbers' => PhoneNumbers::class,
        ];
    }
}
