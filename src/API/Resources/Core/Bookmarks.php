<?php

namespace Qurban\ZendeskAPI\Resources\Core;

use Qurban\ZendeskAPI\Resources\ResourceAbstract;
use Qurban\ZendeskAPI\Traits\Resource\Create;
use Qurban\ZendeskAPI\Traits\Resource\Delete;
use Qurban\ZendeskAPI\Traits\Resource\FindAll;

/**
 * Class Bookmarks
 */
class Bookmarks extends ResourceAbstract
{
    use FindAll;
    use Create;
    use Delete;
}
