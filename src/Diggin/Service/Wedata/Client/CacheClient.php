<?php

namespace Diggin\Service\Wedata\Client;

use Diggin\Service\Wedata\Client
    Diggin\Service\Wedata\Client\ServiceClient;

class CacheClient implements Client
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
