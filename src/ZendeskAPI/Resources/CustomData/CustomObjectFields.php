<?php

namespace Qurban\ZendeskAPI\Resources\CustomData;

use Qurban\ZendeskAPI\Exceptions\ApiResponseException;
use Qurban\ZendeskAPI\Exceptions\AuthException;
use Qurban\ZendeskAPI\HttpClient;
use Qurban\ZendeskAPI\Resources\CustomData\ResourceAbstract;
use stdClass;

/**
 * Class CustomObjectField
 * https://developer.zendesk.com/api-reference/custom-data/custom-objects/custom_object_fields/
 */
class CustomObjectFields extends ResourceAbstract
{

    public function __construct(HttpClient $client, $apiBasePath = 'api/v2/')
    {
        parent::__construct($client, $apiBasePath . "custom_objects/");
    }

    /**
     * @{inheritdoc}
     */
    /*protected $objectName = 'custom_objects_field';*/

    protected $resourceName = 'custom_objects_field';

    /**
     * Find all records for a custom object field
     *
     * @param string|int $name
     * @param array      $params
     *
     * @return stdClass|null
     * @throws ApiResponseException
     * @throws AuthException
     */
    public function findAll(string $name, array $params = []): ?stdClass
    {
        $route = $name . '/fields';

        return $this->client->get($route, $params);
    }

    public function find(string $name, string|int $id, array $params = []): ?stdClass
    {
        $route = $name . '/fields/' . $id;

        return $this->client->get($route, $params);
    }

    /**
     * Create a record for a custom object field
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
        $route = $name . '/fields';

        return $this->client->post($route, ['custom_object_field' => $params]);
    }

    /**
     * update a record for a custom object fields
     *
     * @param string    $name
     * @param string|int $id
     * @param array     $params
     *
     * @return stdClass|null
     * @throws \Qurban\ZendeskAPI\Exceptions\ApiResponseException
     * @throws \Qurban\ZendeskAPI\Exceptions\AuthException
     */
    public function update(string $name, string|int $id, array $params = []): ?stdClass
    {
        $route = $name . '/fields/' . $id;

        return $this->client->put($route, ['custom_object_field' => $params]);
    }

    /**
     * delete a record for a custom object fields
     *
     * @param string    $name
     * @param string|int $id
     *
     * @return stdClass|null
     * @throws \Qurban\ZendeskAPI\Exceptions\ApiResponseException
     * @throws \Qurban\ZendeskAPI\Exceptions\AuthException|\GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $name, string|int $id): ?stdClass
    {
        $route = $name . '/fields/' . $id;

        return $this->client->delete($route);
    }
}
