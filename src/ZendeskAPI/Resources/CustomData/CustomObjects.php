<?php

namespace Qurban\ZendeskAPI\Resources\CustomData;

use Qurban\ZendeskAPI\Exceptions\RouteException;
use Qurban\ZendeskAPI\Traits\Resource\Defaults;

/**
 * Class CustomObjects
 * https://developer.zendesk.com/api-reference/custom-data/custom-objects/custom_objects/
 */
class CustomObjects extends ResourceAbstract
{
    use Defaults;

    /**
     * @{inheritdoc}
     */
    /*protected $objectName = 'custom_objects';*/

    protected $resourceName = 'custom_objects';


    /**
     * @{inheritdoc}
     */
    /*protected function setupRoutes()
    {
        parent::setUpRoutes();
    }*/
}
