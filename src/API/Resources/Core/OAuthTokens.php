<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Resource\Delete;
use Qurban\ZendeskAPI\Traits\Resource\Find;
use Qurban\ZendeskAPI\Traits\Resource\FindAll;

/**
 * Class OAuthTokens
 */
class OAuthTokens extends ResourceAbstract
{
    use FindAll;
    use Find;
    use Delete;

    /**
     * {@inheritdoc}
     */
    protected $objectName = 'token';
    /**
     * {@inheritdoc}
     */
    protected $objectNamePlural = 'tokens';

    /**
     * @var string
     */
    protected $resourceName = 'oauth/tokens';

    protected function setUpRoutes()
    {
        $this->setRoute('current', "$this->resourceName/current.json");
    }

    /**
     * Wrapper for `delete`, called `revoke` in the API docs.
     *
     * @param null $id
     *
     * @return bool
     * @throws \Qurban\ZendeskAPI\Exceptions\MissingParametersException
     */
    public function revoke($id = null)
    {
        return $this->delete($id, 'delete');
    }

    /**
     * Shows the current token
     *
     * @return \stdClass | null
     * @throws \Qurban\ZendeskAPI\Exceptions\RouteException
     */
    public function current()
    {
        return $this->client->get($this->getRoute(__FUNCTION__));
    }
}
