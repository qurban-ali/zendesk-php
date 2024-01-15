<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Resource\FindAll;
use Qurban\ZendeskAPI\Traits\Utility\Pagination\SinglePageStrategy;

/**
 * Class CustomRoles
 * https://developer.zendesk.com/rest_api/docs/core/custom_roles
 */
class CustomRoles extends ResourceAbstract
{
    use FindAll;

    protected function paginationStrategyClass() {
        return SinglePageStrategy::class;
    }
}
