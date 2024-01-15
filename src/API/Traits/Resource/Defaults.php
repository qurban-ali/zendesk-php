<?php

namespace Qurban\ZendeskAPI\Traits\Resource;

/**
 * This trait gives resources access to the default CRUD methods.
 *
 * @package Qurban\ZendeskAPI\Traits\Resource
 */
trait Defaults
{
    use Find;
    use FindAll;
    use Delete;
    use Create;
    use Update;
}
