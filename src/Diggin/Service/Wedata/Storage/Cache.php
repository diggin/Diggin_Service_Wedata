<?php

namespace Diggin\Service\Wedata\Storage;
use Diggin\Service\Wedata\Storage,
    Diggin\Service\Wedata\Database,
    Diggin\Service\Wedata\CallbackFilterIterator,
    Diggin\Service\Wedata\Exception,
    Zend\Cache\Frontend;

class Cache implements Storage
{
    private $cache_prefix = 'diggin_wedata_';

    private $frontend;

    private $searchitemdata_ignore;

    public function __construct(Frontend $frontend)
    {
        $this->frontend = $frontend;
    }
    
    public function setSearchItemDataIgnore($callback)
    {
        $this->searchitemdata_ignore = $callback;
    }

    public function getItems($database)
    {
        $key = $this->filterCacheKey($database);

        if(!$this->frontend->test($key)) {
            throw new Exception\RuntimeException('not stored');
        }

        return $this->frontend->load($key);
    }

    /**
     * NOTES: cache adapter not support paging
     */
    public function storeItems($database, $items)
    {
        $key = $this->filterCacheKey($database);

        $this->frontend->save($items, $key);
    }

    /**
     * search by each item's name
     */
    public function searchItem($database, $name)
    {
        $key = $this->filterCacheKey($database);

        if(!$this->frontend->test($key)) {
            throw new Exception\RuntimeException('not stored');
        }

        $items = $this->frontend->load($key);

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
        $items = $this->frontend->load($cachekey);

        if ($this->searchitemdata_ignore) {
            $items = new CallbackFilterIterator($items, $this->searchitemdata_ignore);
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
            throw new Exception;
        }

        return $this->cache_prefix.$database;
    }
}
