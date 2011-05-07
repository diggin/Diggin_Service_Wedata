<?php

namespace Diggin\Service\Wedata\Client;

use Diggin\Service\Wedata\Wedata,
    Diggin\Service\Wedata\Client,
    Diggin\Service\Wedata\Database,
    Zend\Service\AbstractService;

/**
 * Service API Client
 */
class ServiceClient extends AbstractService implements Client
{
    public function getDatabases()
    {
        $responseBody = $this->makeRequest(Wedata::PATH_GET_DATABASES, \Zend\Http\Client::GET);

        return ;
    }

    /**
     * @param mixed string|Database
     * @param int
     */
    public function getItems($database, $page = 1)
    {
    
    }

    /**
     * Handles all requests to a web service
     * 
     * @param string $path
     * @param string $method Zend\Http\Client's Consts
     * @param array $params parameter for wedata
     * @return mixed
     * @throws Diggin\Service\Wedata\Exception
     */
    protected function makeRequest($path, $method, array $params = array())
    {
        $client = $this->getHttpClient();

        $client->resetParameters();

        $uri = new Url(Wedata::API_URL);
        $uri->setPath($path);

        if (is_array($params) && count($params) > 0) {
            if ($method == \Zend\Http\Client::GET) {
                $client->setParameterGet($params);
            } elseif ($method == \Zend\Http\Client::POST) {
                $client->setParameterPost($params);
            } else {
                $uri->setQuery($params);
            }
        }

        $client->setUri($uri);
        
        $response = $client->request($method);
        
        if (!$response->isSuccessful()) {
             throw new Exception("Http client reported an error: '{$response->getMessage()}'");
        }
        
        //return response switching by Reqest Method
        if ($method == \Zend\Http\Client::GET) {
            return $response->getBody();
        } else {
            $status = $response->getStatus();
            $headers = $response->getHeaders();
            return array($status, $headers);
        }
    }
  
}
