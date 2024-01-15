<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Resource\Find;
use Qurban\ZendeskAPI\Traits\Resource\FindMany;

/**
 * Class JobStatuses
 */
class JobStatuses extends ResourceAbstract
{
    use Find;

    use FindMany;

    /**
     * {@inheritdoc}
     */
    protected $objectName = 'job_status';
    /**
     * {@inheritdoc}
     */
    protected $objectNamePlural = 'job_statuses';
}
