<?php

namespace Diggin\Service\Wedata\Client;

use Diggin\Service\Wedata\Wedata,
    Diggin\Service\Wedata\Client,
    Diggin\Service\Wedata\Databases,
    Diggin\Service\Wedata\Items;

class MockClient implements Client
{
    public function getDatabases()
    {
        $response = array_pop(func_get_args());
        $decode = json_decode($response->getBody());

        return new Databases($decode);
    }

    /**
     * @param mixed string|Database
     * @param int
     * @return Diggin\Service\Wedata\Items Extends ArrayIterator Object.
     */
    public function getItems($database, $page = 1)
    {
        $response = array_pop(func_get_args());
        $decode = json_decode($response->getBody());

        return new Items($decode);
    }
}
