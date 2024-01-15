<?php

namespace Qurban\ZendeskAPI\Resources\Embeddable;

use Qurban\ZendeskAPI\Traits\Resource\ResourceName;

/**
 * Abstract class for Embeddable resources
 */
abstract class ResourceAbstract extends \Qurban\ZendeskAPI\Resources\ResourceAbstract
{
    use ResourceName;

    /**
     * @var string
     **/
    protected $prefix = 'embeddable/api/';

    /**
     * @var string
     */
    protected $apiBasePath = '';
}
