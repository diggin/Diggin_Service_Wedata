<?php

namespace DigginTests\Service\Wedata\Storage;

use Diggin\Service\Wedata\Storage\Cache;

use Zend\Cache\Cache as ZFCache;

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
        $frontendOptions = array(
            'lifetime' => 86400,
            'automatic_serialization' => true,
        );

        $backendOptions = array(
            'cache_dir' => __DIR__.'/_files'
        );
        
        $frontend = ZFCache::factory('Core', 'File', $frontendOptions, $backendOptions);

        return $frontend;
    }

    protected function getItems()
    {
        $items = unserialize(
                   file_get_contents(__DIR__. DIRECTORY_SEPARATOR .'_files'. DIRECTORY_SEPARATOR . 'items.dat')
                 );
        return $items;
    }
}
