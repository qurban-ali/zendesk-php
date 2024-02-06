<?php

namespace Qurban\ZendeskAPI\Resources\CustomData;

use Qurban\ZendeskAPI\Exceptions\ApiResponseException;
use Qurban\ZendeskAPI\Exceptions\AuthException;
use Qurban\ZendeskAPI\HttpClient;
use stdClass;

/**
 * Class CustomObjects
 * https://developer.zendesk.com/api-reference/custom-data/custom-objects/custom_objects/
 */
class CustomObjectsRecords extends ResourceAbstract
{

    public function __construct(HttpClient $client, $apiBasePath = 'api/v2/')
    {
        parent::__construct($client, $apiBasePath."custom_objects/");
    }

    /**
     * @{inheritdoc}
     */
    /*protected $objectName = 'custom_objects';*/

    protected $resourceName = 'custom_objects_records';

    /**
     * Find all records for a custom object
     *
     * @param string $name
     * @param array  $params
     *
     * @return stdClass|null
     * @throws ApiResponseException
     * @throws AuthException
     */
    public function findAll(string $name, array $params = []): ?stdClass
    {
        $route = $name . '/records';

        return $this->client->get(
            $route,
            $params
        );
    }

    /**
     * Create a record for a custom object
     *
     * @param string $name
     * @param array  $params
     *
     * @return stdClass|null
     * @throws ApiResponseException
     * @throws AuthException
     */
    public function create(string $name, array $params = []): ?stdClass
    {
        $route = $name . '/records';

        return $this->client->post(
            $route,
            ['custom_object_record' => $params]
        );
    }


    /**
     * @{inheritdoc}
     */
    /*protected function setupRoutes()
    {
        parent::setUpRoutes();
    }*/
}
