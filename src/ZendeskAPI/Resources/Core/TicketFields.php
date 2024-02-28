<?php
namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Resource\Defaults;
use Qurban\ZendeskAPI\Traits\Utility\InstantiateTrait;

/**
 * The TicketFields class exposes field management methods for tickets
 */
class TicketFields extends ResourceAbstract
{
    use InstantiateTrait;

    use Defaults;

    protected $resourceName = 'ticket_fields';

    /**
     * {@inheritdoc}
     */
    public static function getValidSubResources()
    {
        return [
            'options' => TicketFieldsOptions::class,
        ];
    }
}
