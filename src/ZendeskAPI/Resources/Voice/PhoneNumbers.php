<?php

namespace Qurban\ZendeskAPI\Resources\Voice;

use Qurban\ZendeskAPI\Traits\Resource\Defaults;

/**
 * Class PhoneNumbers
 * https://developer.zendesk.com/rest_api/docs/voice-api/voice
 */
class PhoneNumbers extends ResourceAbstract
{
    use Defaults;

    /**
     * @inheritdoc
     */
    protected function setUpRoutes()
    {
        $this->setRoute('search', "{$this->getResourceName()}/search.json");
    }

    /**
     * Search for available phone numbers.
     *
     * @param array $queryParams
     *
     * @return \stdClass
     * @throws \Qurban\ZendeskAPI\Exceptions\RouteException
     */
    public function search(array $queryParams = [])
    {
        return $this->client->get($this->getRoute(__FUNCTION__), $queryParams);
    }
}
