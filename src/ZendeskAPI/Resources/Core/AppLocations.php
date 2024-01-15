<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Resource\Find;
use Qurban\ZendeskAPI\Traits\Resource\FindAll;

/**
 * The AppLocations class exposes methods seen at
 * https://developer.zendesk.com/rest_api/docs/core/app_locations
 */
class AppLocations extends ResourceAbstract
{
    use Find;
    use FindAll;

    /**
     * {@inheritdoc}
     */
    protected $objectName = 'location';
    /**
     * {@inheritdoc}
     */
    protected $objectNamePlural = 'locations';

    /**
     * @var string
     */
    protected $resourceName = 'apps/locations';
}
