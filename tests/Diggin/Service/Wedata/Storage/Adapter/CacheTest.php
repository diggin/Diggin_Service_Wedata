<?php

namespace DigginTests\Service\Wedata\Storage\Adapter;

use Diggin\Service\Wedata\Storage\Adapter\Cache;

use Zend\Cache\Cache as ZFCache;

class CacheTest extends \PHPUnit_Framework_TestCase
{
    public function testStoreItems()
    {
        $adapter = new Cache($this->factoryCache());

        //var_dump($this->getItems());
        //$adapter->storeItems('AutoPagerize', $this->getItems());
    }

    public function testSearchItem()
    {
        $adapter = new Cache($this->factoryCache());
    
        //var_dump($adapter->searchItem('AutoPagerize', '.*Decay'));
    }

    protected function factoryCache()
    {
        $frontendOptions = array(
            'lifetime' =>  86400,
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
