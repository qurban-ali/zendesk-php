<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Exceptions\MissingParametersException;
use Qurban\ZendeskAPI\Exceptions\RouteException;
use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Resource\Create;
use Qurban\ZendeskAPI\Traits\Resource\CreateMany;
use Qurban\ZendeskAPI\Traits\Resource\Delete;
use Qurban\ZendeskAPI\Traits\Resource\DeleteMany;
use Qurban\ZendeskAPI\Traits\Resource\Find;
use Qurban\ZendeskAPI\Traits\Resource\FindAll;
use Qurban\ZendeskAPI\Traits\Utility\InstantiateTrait;

/**
 * Class GroupMemberships
 * https://developer.zendesk.com/rest_api/docs/core/group_memberships
 */
class GroupMemberships extends ResourceAbstract
{
    use InstantiateTrait;

    use Create;
    use Delete;
    use Find;
    use FindAll;

    use CreateMany;
    use DeleteMany;

    /**
     * {@inheritdoc}
     */
    protected function setUpRoutes()
    {
        $this->setRoutes([
            'assignable'  => "{$this->resourceName}/assignable.json",
            'makeDefault' => 'users/{userId}/group_memberships/{id}/make_default.json',
        ]);
    }

    /**
     * Returns a route and replaces tokenized parts of the string with
     * the passed params
     *
     * @param       $name
     * @param array $params
     *
     * @return mixed The default routes, or if $name is set to `findAll`, any of the following formats
     * based on the parent chain
     * GET /api/v2/groups.json
     * GET /api/v2/users/{user_id}/groups.json
     *
     * @throws \Exception
     */
    public function getRoute($name, array $params = [])
    {
        $lastChained = $this->getLatestChainedParameter([self::class]);

        $chainableRoutes = ['findAll', 'find', 'create', 'delete', 'assignable'];

        if ((empty($lastChained)) || ! (in_array($name, $chainableRoutes))) {
            return parent::getRoute($name, $params);
        }

        $chainedResourceId    = reset($lastChained);
        $chainedResourceNames = array_keys($lastChained);
        $chainedResourceName  = (new $chainedResourceNames[0]($this->client))->resourceName;

        if ($name === 'assignable' && $chainedResourceName === 'groups') {
            return "{$chainedResourceName}/{$chainedResourceId}/memberships/assignable.json";
        }

        if ($name === 'create' && $chainedResourceName === 'users') {
            return "{$chainedResourceName}/{$chainedResourceId}/{$this->resourceName}.json";
        }

        if (in_array($name, ['find', 'delete']) && $chainedResourceName === 'users') {
            return "{$chainedResourceName}/{$chainedResourceId}/{$this->resourceName}/{$params['id']}.json";
        }

        if ($name === 'findAll') {
            if ($chainedResourceName === 'groups') {
                return "{$chainedResourceName}/{$chainedResourceId}/memberships.json";
            } elseif ($chainedResourceName === 'users') {
                return "{$chainedResourceName}/{$chainedResourceId}/group_memberships.json";
            }
        }

        throw new RouteException('Route not found.');
    }

    /**
     * List Assignable Memberships
     *
     * @param array $params
     *
     * @return mixed
     */
    public function assignable(array $params = [])
    {
        return $this->client->get($this->getRoute(__FUNCTION__), $params);
    }

    /**
     * Sets the default group membership of a given user.
     *
     * @param array $params
     *
     * @return \stdClass | null
     * @throws MissingParametersException
     */
    public function makeDefault($params = [])
    {
        $params = $this->addChainedParametersToParams($params, ['id' => self::class, 'userId' => Users::class]);

        if (! $this->hasKeys($params, ['id', 'userId'])) {
            throw new MissingParametersException(__METHOD__, ['id', 'userId']);
        }

        return $this->client->put($this->getRoute(__FUNCTION__, $params));
    }
}
