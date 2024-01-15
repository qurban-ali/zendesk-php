<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Exceptions\MissingParametersException;
use Qurban\ZendeskAPI\Http;
use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Resource\Delete;
use Qurban\ZendeskAPI\Traits\Resource\DeleteMany;
use Qurban\ZendeskAPI\Traits\Resource\Find;
use Qurban\ZendeskAPI\Traits\Resource\FindAll;

/**
 * The SuspendedTickets class exposes view management methods
 * https://developer.zendesk.com/rest_api/docs/core/suspended_tickets
 */
class SuspendedTickets extends ResourceAbstract
{
    use Find;
    use FindAll;
    use Delete;

    use DeleteMany;

    /**
     * {@inheritdoc}
     */
    protected function setUpRoutes()
    {
        $this->setRoutes([
            'recover'     => "{$this->resourceName}/{id}/recover.json",
            'recoverMany' => "{$this->resourceName}/recover_many.json",
        ]);
    }

    /**
     * Recovering suspended tickets.
     *
     * @param $id
     *
     * @return \stdClass | null
     * @throws MissingParametersException
     */
    public function recover($id = null)
    {
        if (empty($id)) {
            $id = $this->getChainedParameter(self::class);
        }

        if (empty($id)) {
            throw new MissingParametersException(__METHOD__, ['id']);
        }

        return $this->client->put($this->getRoute(__FUNCTION__, ['id' => $id]));
    }

    /**
     * Recovering suspended tickets.
     *
     * @param array $ids
     *
     * @return \stdClass | null
     * @throws MissingParametersException
     * @throws \Qurban\ZendeskAPI\Exceptions\ApiResponseException
     * @throws \Qurban\ZendeskAPI\Exceptions\RouteException
     *
     */
    public function recoverMany(array $ids)
    {
        if (! is_array($ids)) {
            throw new MissingParametersException(__METHOD__, ['ids']);
        }

        $response = Http::send(
            $this->client,
            $this->getRoute(__FUNCTION__),
            [
                'method'      => 'PUT',
                'queryParams' => ['ids' => implode(',', $ids)],
            ]
        );

        return $response;
    }
}
