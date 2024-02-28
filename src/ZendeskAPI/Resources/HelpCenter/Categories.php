<?php

namespace Qurban\ZendeskAPI\Resources\HelpCenter;

use Qurban\ZendeskAPI\Traits\Resource\Defaults;
use Qurban\ZendeskAPI\Traits\Resource\Locales;
use Qurban\ZendeskAPI\Traits\Utility\InstantiateTrait;

/**
 * Class Categories
 * https://developer.zendesk.com/rest_api/docs/help_center/categories
 * @method Sections sections()
 */
class Categories extends ResourceAbstract
{
    use InstantiateTrait;
    use Defaults;
    use Locales;

    /**
     * {@inheritdoc}
     */
    protected $objectName = 'category';

    /**
     * {@inheritdoc}
     */
    public static function getValidSubResources()
    {
        return [
            'sections' => Sections::class,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function setUpRoutes()
    {
        $this->setRoute('updateSourceLocale', "{$this->resourceName}/{categoryId}/source_locale.json");
    }
}
