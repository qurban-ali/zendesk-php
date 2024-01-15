<?php

namespace App\Packages\Qurban\ZendeskAPI\Resources\CustomData;

use Qurban\ZendeskAPI\Traits\Resource\ResourceName;

/**
 * Abstract class for HelpCenter resources
 */
abstract class ResourceAbstract extends \Qurban\ZendeskAPI\Resources\ResourceAbstract
{
    use ResourceName;

    /**
     * @var $prefix
     **/
    protected $prefix = 'custom_objects/';
}
