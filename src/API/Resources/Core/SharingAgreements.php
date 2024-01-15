<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Resource\FindAll;
use Qurban\ZendeskAPI\Traits\Utility\Pagination\SinglePageStrategy;

/**
 * The SharingAgreements class
 * https://developer.zendesk.com/rest_api/docs/core/sharing_agreements
 */
class SharingAgreements extends ResourceAbstract
{
    use FindAll;

    protected function paginationStrategyClass() {
        return SinglePageStrategy::class;
    }
}
