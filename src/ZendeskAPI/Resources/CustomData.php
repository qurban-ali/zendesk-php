<?php

namespace Qurban\ZendeskAPI\Resources;

use Qurban\ZendeskAPI\Resources\CustomData\CustomObjects;
use Qurban\ZendeskAPI\HttpClient;
use Qurban\ZendeskAPI\Resources\HelpCenter\Categories;
use Qurban\ZendeskAPI\Resources\HelpCenter\Sections;
use Qurban\ZendeskAPI\Resources\HelpCenter\Articles;
use Qurban\ZendeskAPI\Traits\Utility\ChainedParametersTrait;
use Qurban\ZendeskAPI\Traits\Utility\InstantiatorTrait;

/**
 * This class serves as a container to allow $this->client->helpCenter
 *
 * @method Categories categories()
 * @method Sections sections()
 * @method Articles articles()
 */
class CustomData
{
    use ChainedParametersTrait;
    use InstantiatorTrait;

    /**
     * Sets the client to be used
     *
     * @param HttpClient $client
     */
    public function __construct(
        protected HttpClient $client
    )
    {}

    /**
     * @inheritdoc
     * @return array
     */
    public static function getValidSubResources()
    {
        return [
            'customObjects'    => CustomObjects::class,
        ];
    }
}
