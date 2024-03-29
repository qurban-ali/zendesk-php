<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Exceptions\MissingParametersException;
use Qurban\ZendeskAPI\Traits\Resource\Create;
use Qurban\ZendeskAPI\Traits\Resource\Find;
use Qurban\ZendeskAPI\Traits\Resource\FindAll;

/**
 * Class Satisfaction Ratings
 * https://developer.zendesk.com/rest_api/docs/core/satisfaction_ratings
 */
class SatisfactionRatings extends ResourceAbstract
{
    use Create {
        create as traitCreate;
    }
    use Find;
    use FindAll;

    /**
     * Declares routes to be used by this resource.
     */
    protected function setUpRoutes()
    {
        parent::setUpRoutes();

        $this->setRoutes([
            'create' => 'tickets/{ticket_id}/satisfaction_rating.json'
        ]);
    }

    /**
     * Creates a Satisfaction Rating
     * https://developer.zendesk.com/rest_api/docs/core/satisfaction_ratings#create-a-satisfaction-rating
     *
     * @param array $queryParams
     *
     * @throws MissingParametersException
     * @throws \Exception
     *
     * @return \stdClass | null
     */
    public function create(array $queryParams = [])
    {
        $queryParams = $this->addChainedParametersToParams($queryParams, ['ticket_id' => Tickets::class]);

        if (! $this->hasKeys($queryParams, ['ticket_id'])) {
            throw new MissingParametersException(__METHOD__, ['ticket_id']);
        }

        return $this->traitCreate($queryParams);
    }
}
