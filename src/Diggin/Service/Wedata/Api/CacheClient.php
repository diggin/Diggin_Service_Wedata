<?php

namespace Diggin\Service\Wedata\Api;

use Diggin\Service\Wedata\Api,
    Diggin\Service\Wedata\Api\Client;

class CacheClient implements Api
{

    public function getDatabases()
    {
        return ;
    }

    /**
     * @param mixed string|Database
     * @param int
     */
    public function getItems($database, $page = 1)
    {
    
    }

    protected function getServiceClient()
    {
        
    }

}
