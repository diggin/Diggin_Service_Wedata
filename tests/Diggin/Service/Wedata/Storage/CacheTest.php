<?php

namespace DigginTests\Service\Wedata\Storage;

use Diggin\Service\Wedata\Storage\Cache;
use Zend\Cache\StorageFactory as CacheStorageFactory;

class CacheTest extends \PHPUnit_Framework_TestCase
{
    /**
    public function testStoreItems()
    {
        $adapter = new Cache($this->factoryCache());
        $adapter->storeItems('AutoPagerize', $this->getItems());

        $this->assertEquals($this->getItems(), $adapter->getItems('AutoPagerize'));
    }*/

    public function testSearchItem()
    {
        $item = $this->getStorage()->searchItem('AutoPagerize', '.*Decay');

        $this->assertEquals('Urban Decay', $item->getName());
    }

    public function testSearchItemData()
    {
        $storage = $this->getStorage();
        $item = $storage->searchItemData('AutoPagerize', 'url', 'http://magazine.kakaku.com/mag/page?');
        $this->assertEquals('mimi-neko', $item->getCreatedBy(), "item\n".var_export($item, true));

        $storage->setSearchItemDataIgnore(function ($current, $key, $iterator) {
           return 'mimi-neko' != $current->getCreatedBy();                               
        });

    }

    public function getStorage()
    {
        $storage = new Cache($this->factoryCache());
        $storage->setSearchItemDataIgnore(function ($current, $key, $iterator) {
        //if ('^https?://.' != $item['data']['url'] && (preg_match('>'.$item['data']['url'].'>', $url) == 1)) {
                $data = $current->getData();
                return $data->url !== '^https?://[^/]+';
            });
        return $storage;
    
    }

    public function factoryCache()
    {
        $cacheStorageAdapter = CacheStorageFactory::factory(array(
            'adapter' => array(
                'name' => 'Filesystem',
                'options' => array(
                    'cache_dir' => TESTS_DIGGIN_SERVICE_WEDATA_DATADIR,
                     //'ttl' => 86400
                     )
                ),
            'plugins' => array(
                    'serializer'
                )
            ));

        return $cacheStorageAdapter;
    }

    protected function getItems()
    {
        $items = unserialize(
                   file_get_contents(TESTS_DIGGIN_SERVICE_WEDATA_DATADIR. '/autopagerize_items.dat')
                 );
        return $items;
    }
}
