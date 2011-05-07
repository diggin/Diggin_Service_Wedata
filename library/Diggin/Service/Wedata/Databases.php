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

    /**
     * Map to database
     *
     * Example:
     * "updated_at": "2008-10-20T17:51:29+09:00",
     * "optional_keys": "birthyear birthmonth birthdate blood height weight production",
     * "required_keys": "name bust waist hip",
     * "created_by": "ikeniecom",
     * "description": "",
     * "permit_other_keys": true,
     * "resource_url": "http:\/\/wedata.net\/databases\/idol3size",
     * "created_at": "2008-10-20T17:51:29+09:00"
     */
    protected static function mapToDatabase($stdObject)
    {
        $database = new Database;
        $database->setName($stdObject->name);
        $database->setUpdatedAt($stdObject->updated_at);
        $database->setOptionalKeys($stdObject->optional_keys);
        $database->setRequiredKeys($stdObject->required_keys);
        $database->setCreatedBy($stdObject->created_by);
        $database->setDescription($stdObject->description);
        $database->setPermitOtherKeys($stdObject->permit_other_keys);
        $database->setResourceUrl($stdObject->resource_url);
        $database->setCreatedAt($stdObject->created_at);
        
        return $database;
    }
}

