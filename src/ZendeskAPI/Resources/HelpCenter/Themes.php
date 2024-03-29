<?php

namespace Qurban\ZendeskAPI\Resources\HelpCenter;

use Qurban\ZendeskAPI\Traits\Resource\Defaults;

/**
 * Class Articles
 * https://developer.zendesk.com/api-reference/help_center/help-center-api/theming/
 */
class Themes extends ResourceAbstract
{
    use Defaults;

    /**
     * @{inheritdoc}
     */
    protected $objectName = 'themes';

    protected $resourceName = 'guide/theming/themes';

    protected function setUpRoutes()
    {
        $this->setRoutes([
            'findAll' => "{$this->resourceName}",
            'find' => "{$this->resourceName}/{themeId}",
        ]);
    }
}
