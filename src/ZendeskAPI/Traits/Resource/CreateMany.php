<?php

namespace Qurban\ZendeskAPI\Traits\Resource;

use Qurban\ZendeskAPI\Exceptions\ResponseException;
use Qurban\ZendeskAPI\Exceptions\RouteException;

/**
 * Allows resources to call a bulk create endpoint.
 *
 */
trait CreateMany
{
    /**
     * Create multiple new resources
     *
     * @param array $params
     *
     * @throws ResponseException
     * @throws \Exception
     *
     * @return \stdClass | null
     */
    public function createMany(array $params)
    {
        try {
            $route = $this->getRoute(__FUNCTION__);
        } catch (RouteException $e) {
            if (! isset($this->resourceName)) {
                $this->resourceName = $this->getResourceNameFromClass();
            }

            $route = $this->resourceName . '/create_many.json';
            $this->setRoute('createMany', $route);
        }

        return $this->client->post($route, [$this->objectNamePlural => $params]);
    }
}
