<?php

namespace Diggin\Service\Wedata;

class Database
{
    private $name;
    private $updated_at;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    //public function getRequiredKeysAsArray()
}

