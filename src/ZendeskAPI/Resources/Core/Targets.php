<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Resource\Defaults;
use Qurban\ZendeskAPI\Traits\Utility\Pagination\SinglePageStrategy;

/**
 * The Tags class exposes methods as detailed on https://developer.zendesk.com/rest_api/docs/core/targets
 */
class Targets extends ResourceAbstract
{
    use Defaults;

    protected function paginationStrategyClass() {
        return SinglePageStrategy::class;
    }
}
