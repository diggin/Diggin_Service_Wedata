<?php

namespace Diggin\Service\Wedata\Storage;
use Diggin\Service\Wedata\Storage,
    Diggin\Service\Wedata\Database,
    Diggin\Service\Wedata\CallbackFilterIterator,
    Diggin\Service\Wedata\Exception,
    Zend\Cache\Storage\Adapter as CacheStorage;

class Cache implements Storage
{
    private $cachePrefix = 'diggin_wedata_';

    private $cacheStorage;

    private $searchItemdataIgnore;

    public function __construct(CacheStorage $cacheStorage)
    {
        $this->cacheStorage = $cacheStorage;
    }
    
    public function setSearchItemDataIgnore($callback)
    {
        $this->searchItemdataIgnore = $callback;
    }

    public function getItems($database)
    {
        $key = $this->filterCacheKey($database);

        if(!$this->cacheStorage->hasItem($key)) {
            throw new Exception\RuntimeException('not stored');
        }

        return $this->cacheStorage->getItem($key);
    }

    /**
     * NOTES: cache adapter not support paging
     */
    public function storeItems($database, $items)
    {
        $cachekey = $this->filterCacheKey($database);

        $this->cacheStorage->setItem($cachekey, $items);
    }

    /**
     * search by each item's name
     */
    public function searchItem($database, $name)
    {
        $cachekey = $this->filterCacheKey($database);

        if (!$this->cacheStorage->hasItem($cachekey)) {
            throw new Exception\RuntimeException('not stored');
        }

        $items = $this->cacheStorage->getItem($cachekey);

        foreach ($items as $item) {
            if(preg_match('>'.$name.'>', $item->getName())) {
                return $item;
            }
        }

        return false;
    }

    public function searchItemData($database, $key, $term)
    {
        $cachekey = $this->filterCacheKey($database);

        if (!$this->cacheStorage->hasItem($cachekey)) {
            throw new Exception\RuntimeException('not stored');
        }

        $items = $this->cacheStorage->getItem($cachekey);

        if ($this->searchItemdataIgnore) {
            $items = new CallbackFilterIterator($items, $this->searchItemdataIgnore);
        }

        foreach ($items as $item) {
            $data = $item->getData();
            if(preg_match('>'.$data->$key.'>', $term)) {
                return $item;
            }
        }

        return false;
    }

    protected function filterCacheKey($database)
    {
        if ($database instanceof Database) {
            $database = $database->getName();
        } 
        if (!is_string($database)) {
            throw new Exception\InvalidArgumentException('$database should be Diggin\Service\Wedata\Database object or string');
        }

        return $this->cachePrefix.$database;
    }
}
