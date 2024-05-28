<?php

namespace Qurban\ZendeskAPI\Resources;

use Qurban\ZendeskAPI\HttpClient;
use Qurban\ZendeskAPI\Resources\CustomData\CustomObjects;
use Qurban\ZendeskAPI\Resources\CustomData\CustomObjectsRecords;
use Qurban\ZendeskAPI\Traits\Utility\ChainedParametersTrait;
use Qurban\ZendeskAPI\Resources\CustomData\CustomObjectFields;
use Qurban\ZendeskAPI\Traits\Utility\InstantiateTrait;

/**
 * This class serves as a container to allow $this->client->helpCenter
 *
 * @method CustomObjects customObjects()
 * @method CustomObjectsRecords customObjectsRecords()
 * @method CustomObjectFields customObjectFields()
 */
class CustomData
{

    use ChainedParametersTrait;
    use InstantiateTrait;

    /**
     * Sets the client to be used
     *
     * @param HttpClient $client
     */
    public function __construct(protected HttpClient $client)
    {
    }

    /**
     * @inheritdoc
     * @return array
     */
    public static function getValidSubResources(): array
    {
        return [
            'customObjects'        => CustomObjects::class,
            'customObjectsRecords' => CustomObjectsRecords::class,
            'customObjectFields'   => CustomObjectFields::class,
        ];
    }
}
