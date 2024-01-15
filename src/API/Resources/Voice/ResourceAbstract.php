<?php

namespace Qurban\ZendeskAPI\Resources\Voice;

use Qurban\ZendeskAPI\Traits\Resource\ResourceName;

/**
 * Abstract class for Voice resources
 */
abstract class ResourceAbstract extends \Qurban\ZendeskAPI\Resources\ResourceAbstract
{
    use ResourceName;

    /**
     * @var $prefix
     **/
    protected $prefix = 'channels/voice/';
}
