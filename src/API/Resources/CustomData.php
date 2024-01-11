<?php

namespace Zendesk\API\Resources;

use App\Packages\Zendesk\API\Resources\CustomData\CustomObjects;
use Zendesk\API\HttpClient;
use Zendesk\API\Resources\HelpCenter\Categories;
use Zendesk\API\Resources\HelpCenter\Sections;
use Zendesk\API\Resources\HelpCenter\Articles;
use Zendesk\API\Traits\Utility\ChainedParametersTrait;
use Zendesk\API\Traits\Utility\InstantiatorTrait;

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
