<?php

namespace Diggin\Service\Wedata;

use Diggin\Service\Wedata\Database;

class Databases extends \ArrayIterator
{
    public function current()
    {
        $stdObject = parent::current();
        
        return static::mapToDatabase($stdObject);
    }

    protected static function mapToDatabase($stdObject)
    {
        $database = new Database;
        $database->setName($stdObject->name);
        
        return $database;
    }
}

