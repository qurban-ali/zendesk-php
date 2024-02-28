<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Utility\InstantiateTrait;

/**
 * Class DynamicContent
 *
 * @method DynamicContentItems items()
 */
class DynamicContent extends ResourceAbstract
{
    use InstantiateTrait;

    /**
     * {@inheritdoc}
     */
    public static function getValidSubResources()
    {
        return [
            'items' => DynamicContentItems::class,
        ];
    }
}
