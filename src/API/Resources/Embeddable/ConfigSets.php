<?php

namespace Qurban\ZendeskAPI\Resources\Embeddable;

use Qurban\ZendeskAPI\Traits\Resource\Create;
use Qurban\ZendeskAPI\Traits\Resource\Update;

/**
 * Class ConfigSets
 * Requires web_widget:write scoped oauth token
 */
class ConfigSets extends ResourceAbstract
{
    use Create;
    use Update;

    /**
     * {@inheritdoc}
     */
    protected $objectName = 'config_set';

    /**
     * {@inheritdoc}
     */
    protected $objectNamePlural = 'config_sets';
}
