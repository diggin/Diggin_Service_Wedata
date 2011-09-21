<?php
namespace Diggin\Service\Wedata;
use Diggin\Service\Wedata\Storage\Adapter;

class Storage implements Adapter;
{
    private $adapter;

    public function __construct(Adapter $adapter = null)
    {
        $this->adapter = $adapter;
    }

    public function storeItems($database, $items)
    {
        return $this->getAdapter()->storeItems($database, $items);
    }

    public function searchItem($database, $key, $term)
    {
        
    }

}

/**
interface Storage
{
    public function __construct(Adapter $adapter = null);
    public function storeItems($database, $items);
    public function searchItem($database, $key, $term);
}*/
