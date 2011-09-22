<?php

namespace Diggin\Service\Wedata\Storage\Adapter;
use Diggin\Service\Wedata\Storage\Adapter,
    Diggin\Service\Wedata\Database,
    Zend\Cache\Frontend;

class Cache implements Adapter
{
    private $cache_prefix = 'diggin_wedata_';

    private $frontend;

    public function __construct(Frontend $frontend)
    {
        $this->frontend = $frontend;
    }

    /**
     * NOTES: not support with page
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
        $items = $this->frontend->load($key);

        foreach ($items as $item) {
            if(preg_match('>'.$name.'>', $item->getName())) {
                return $item;
            }
        }

        return false;
    }

    // public function searchItemData($database, $key, $term, $callback_ignore_case)
    public function searchItemData($database, $key, $term)
    {
        $key = $this->filterCacheKey($database);
        $items = $this->frontend->load($key);

        foreach ($items as $item) {
            $data = $item->getData();
            if(preg_match('>'.$term.'>', $data->$key)) {
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
