<?php

namespace Qurban\ZendeskAPI\Traits\Resource;

use Qurban\ZendeskAPI\Exceptions\RouteException;

trait FindAll
{
    use Pagination;


    /**
     * List all of this resource
     *
     * @param array  $params
     *
     * @param string $routeKey
     *
     * @return \stdClass | null
     * @throws \Qurban\ZendeskAPI\Exceptions\AuthException
     * @throws \Qurban\ZendeskAPI\Exceptions\ApiResponseException
     */
    public function findAll(array $params = [], $routeKey = __FUNCTION__)
    {
        try {
            $route = $this->getRoute($routeKey, $params);
        } catch (RouteException $e) {
            if (!isset($this->resourceName)) {
                $this->resourceName = $this->getResourceNameFromClass();
            }

            $route = $this->resourceName . '.json';

            $this->setRoute(__FUNCTION__, $route);
        }

        return $this->client->get(
            $route,
            $params
        );
    }
}
