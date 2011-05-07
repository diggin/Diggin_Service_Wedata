<?php

namespace Diggin\Service\Wedata;

use Diggin\Service\Wedata\Client,
    Diggin\Service\Wedata\Database;

// やっぱいらない
class DatabaseRepository
{
    private $client;
    private $database;

    public function __construct(Client $client, Database $database)
    {
        $this->client = $client;
        $this->database = $database;
    }

    protected function validateRequiredKey()
    {
    }

    public function updateItem($model)
    {
        //check valid instance 
        // $model instanceof LDRFullFeed.. etc.
    }

    public function searchItem($key, $term)
    {}
}
