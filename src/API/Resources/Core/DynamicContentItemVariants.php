<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Resource\Create;
use Qurban\ZendeskAPI\Traits\Resource\CreateMany;
use Qurban\ZendeskAPI\Traits\Resource\Delete;
use Qurban\ZendeskAPI\Traits\Resource\Find;
use Qurban\ZendeskAPI\Traits\Resource\FindAll;
use Qurban\ZendeskAPI\Traits\Resource\Update;
use Qurban\ZendeskAPI\Traits\Resource\UpdateMany;

/**
 * Class DynamicContentItemVariants
 */
class DynamicContentItemVariants extends ResourceAbstract
{
    use Create;
    use Delete;
    use Update;
    use Find;
    use FindAll;

    use CreateMany;
    use UpdateMany;

    /**
     * {@inheritdoc}
     */
    protected $objectName = 'variant';
    /**
     * {@inheritdoc}
     */
    protected $objectNamePlural = 'variants';

    /**
     * {@inheritdoc}
     */
    protected function setUpRoutes()
    {
        $this->setRoutes(
            [
                'findAll'    => 'dynamic_content/items/{item_id}/variants.json',
                'find'       => 'dynamic_content/items/{item_id}/variants/{id}.json',
                'create'     => 'dynamic_content/items/{item_id}/variants.json',
                'delete'     => 'dynamic_content/items/{item_id}/variants.json',
                'update'     => 'dynamic_content/items/{item_id}/variants/{id}.json',
                'createMany' => 'dynamic_content/items/{item_id}/variants/create_many.json',
                'updateMany' => 'dynamic_content/items/{item_id}/variants/update_many.json',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute($name, array $params = [])
    {
        $params = $this->addChainedParametersToParams($params, ['item_id' => DynamicContentItems::class]);

        return parent::getRoute($name, $params);
    }
}
