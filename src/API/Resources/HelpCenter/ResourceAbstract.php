<?php

namespace Qurban\ZendeskAPI\Resources\HelpCenter;

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
    protected $prefix = 'help_center/';
}
