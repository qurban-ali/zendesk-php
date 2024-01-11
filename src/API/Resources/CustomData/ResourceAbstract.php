<?php

namespace App\Packages\Zendesk\API\Resources\CustomData;

use Zendesk\API\Traits\Resource\ResourceName;

/**
 * Abstract class for HelpCenter resources
 */
abstract class ResourceAbstract extends \Zendesk\API\Resources\ResourceAbstract
{
    use ResourceName;

    /**
     * @var $prefix
     **/
    protected $prefix = 'custom_objects/';
}
