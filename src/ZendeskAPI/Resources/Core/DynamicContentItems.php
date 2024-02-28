<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Resource\Defaults;
use Qurban\ZendeskAPI\Traits\Utility\InstantiateTrait;

/**
 * Class DynamicContentItems
 *
 * @method DynamicContentItemVariants variants()
 */
class DynamicContentItems extends ResourceAbstract
{
    use InstantiateTrait;

    use Defaults;

    /**
     * {@inheritdoc}
     */
    protected $objectName = 'item';
    /**
     * {@inheritdoc}
     */
    protected $objectNamePlural = 'items';

    /**
     * @var string
     */
    protected $resourceName = 'dynamic_content/items';

    /**
     * {@inheritdoc}
     */
    public static function getValidSubResources()
    {
        return [
            'variants' => DynamicContentItemVariants::class,
        ];
    }
}
