<?php

namespace Qurban\ZendeskAPI\Resources;

use Qurban\ZendeskAPI\HttpClient;
use Qurban\ZendeskAPI\Resources\HelpCenter\Categories;
use Qurban\ZendeskAPI\Resources\HelpCenter\Sections;
use Qurban\ZendeskAPI\Resources\HelpCenter\Articles;
use Qurban\ZendeskAPI\Resources\HelpCenter\Themes;
use Qurban\ZendeskAPI\Traits\Utility\ChainedParametersTrait;
use Qurban\ZendeskAPI\Traits\Utility\InstantiatorTrait;

/**
 * This class serves as a container to allow $this->client->helpCenter
 *
 * @method Categories categories()
 * @method Sections sections()
 * @method Articles articles()
 * @method Themes themes()
 */
class HelpCenter
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
    public static function getValidSubResources(): array
    {
        return [
            'categories'    => Categories::class,
            'sections'      => Sections::class,
            'articles'      => Articles::class,
            'themes'        => Themes::class,
        ];
    }
}
