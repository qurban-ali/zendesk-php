<?php

namespace Qurban\ZendeskAPI\Resources\Talk;

use Qurban\ZendeskAPI\Exceptions\MissingParametersException;
use Qurban\ZendeskAPI\Exceptions\ResponseException;
use Qurban\ZendeskAPI\Http;
use Qurban\ZendeskAPI\Traits\Utility\InstantiateTrait;

/**
 * The Stats class exposes key methods for reading Talk Stats.
 */
class Stats extends ResourceAbstract
{
    /**
     * Declares routes to be used by this resource.
     */
    protected function setUpRoutes()
    {
        parent::setUpRoutes();

        $this->setRoutes([
            "currentQueue"      => "{$this->prefix}stats/current_queue_activity.json",
            "accountOverview"   => "{$this->prefix}stats/account_overview.json",
            "agentsOverview"    => "{$this->prefix}stats/agents_overview.json",
            "agentsActivity"    => "{$this->prefix}stats/agents_activity.json",
        ]);
    }

    /**
     * Shows current queue.
     *
     * @throws \Exception
     * @return \stdClass | null
     * @throws \Qurban\ZendeskAPI\Exceptions\AuthException
     * @throws \Qurban\ZendeskAPI\Exceptions\ApiResponseException
     */
    public function currentQueue()
    {
        $route = $this->getRoute(__FUNCTION__);

        return $this->client->get($route);
    }
    
    /**
     * Account overview.
     *
     * @throws \Exception
     * @return \stdClass | null
     * @throws \Qurban\ZendeskAPI\Exceptions\AuthException
     * @throws \Qurban\ZendeskAPI\Exceptions\ApiResponseException
     */
    public function accountOverview()
    {
        $route = $this->getRoute(__FUNCTION__);

        return $this->client->get($route);
    }
    
    /**
     * Agents overview.
     *
     * @throws \Exception
     * @return \stdClass | null
     * @throws \Qurban\ZendeskAPI\Exceptions\AuthException
     * @throws \Qurban\ZendeskAPI\Exceptions\ApiResponseException
     */
    public function agentsOverview()
    {
        $route = $this->getRoute(__FUNCTION__);

        return $this->client->get($route);
    }
    
    /**
     * Agents activity.
     *
     * @throws \Exception
     * @return \stdClass | null
     * @throws \Qurban\ZendeskAPI\Exceptions\AuthException
     * @throws \Qurban\ZendeskAPI\Exceptions\ApiResponseException
     */
    public function agentsActivity()
    {
        $route = $this->getRoute(__FUNCTION__);

        return $this->client->get($route);
    }
}
