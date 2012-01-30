<?php

namespace DigginTests\Service\Wedata\Storage;

use Diggin\Service\Wedata\Storage\Cache,
    Zend\Cache\StorageFactory as CacheStorageFactory;

class CacheTest extends \PHPUnit_Framework_TestCase
{
    public function testStoreItems()
    {
        $adapter = new Cache($this->factoryCache());
        $adapter->storeItems('AutoPagerize', $this->getItems());

        $this->assertEquals($this->getItems(), $adapter->getItems('AutoPagerize'));
    }

    public function testSearchItem()
    {
        $adapter = new Cache($this->factoryCache());
    
        $item = $adapter->searchItem('AutoPagerize', '.*Decay');

        $this->assertEquals('Urban Decay', $item->getName());
    }

    public function testSearchItemData()
    {
        $adapter = new Cache($this->factoryCache());
        $item = $adapter->searchItemData('AutoPagerize', 'url', 'http://magazine.kakaku.com/mag/page?');
        $this->assertEquals('mimi-neko', $item->getCreatedBy());

        $adapter->setSearchItemDataIgnore(function ($current, $key, $iterator) {
           return 'mimi-neko' != $current->getCreatedBy();                               
        });

        $item = $adapter->searchItemData('AutoPagerize', 'url', 'http://magazine.kakaku.com/mag/page?');

        $this->assertFalse($item);
    }

    public function factoryCache()
    {
        $cacheStorageAdapter = CacheStorageFactory::factory(array(
            'adapter' => array(
                'name' => 'Filesystem',
                'options' => array(
                    'cache_dir' => __DIR__.'/_files',
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
                   file_get_contents(__DIR__. DIRECTORY_SEPARATOR .'_files'. DIRECTORY_SEPARATOR . 'items.dat')
                 );
        return $items;
    }
}
