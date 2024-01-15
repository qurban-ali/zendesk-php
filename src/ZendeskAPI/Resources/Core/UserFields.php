<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Http;
use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Resource\Defaults;

/**
 * The UserFields class exposes fields on the user profile page
 */
class UserFields extends ResourceAbstract
{
    use Defaults;

    /**
     * {@inheritdoc}
     */
    protected function setUpRoutes()
    {
        $this->setRoute('reorder', "{$this->resourceName}/reorder.json");
    }

    /**
     * Reorder user fields
     *
     * @param array $params
     *
     * @return \stdClass | null
     */
    public function reorder(array $params)
    {
        $postFields = ['user_field_ids' => $params];

        $response = Http::send(
            $this->client,
            $this->getRoute(__FUNCTION__),
            ['postFields' => $postFields, 'method' => 'PUT']
        );

        return $response;
    }
}
