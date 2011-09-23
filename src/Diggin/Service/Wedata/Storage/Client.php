<?php

namespace Diggin\Service\Wedata\Storage;

use Diggin\Service\Wedata\Storage;

class Client implements Storage;
{
    private $adapter;

    public function __construct(Storage $adapter = null)
    {
        $this->adapter = $adapter;
    }

    public function storeItems($database, $items)
    {
        return $this->getAdapter()->storeItems($database, $items);
    }

    public function searchItem($database, $key, $term)
    {
        return $this->getAdapter()->searchItem($database, $key, $term);
    }
}

