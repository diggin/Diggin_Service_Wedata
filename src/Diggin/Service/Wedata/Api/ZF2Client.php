<?php
namespace Diggin\Service\Wedata\Api;

use Diggin\Service\Wedata\Api\AbstractClient,
    Zend\Json\Json,
    Zend\Uri\UriFactory,
    Zend\Http\Request,
    Zend\Http\Client;

/**
 * Wedata Service API Client
 */
class ZF2Client extends AbstractClient
{
    protected $client;

    public function setHttpClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    public function getHttpClient()
    {
        if (!$this->client) {
            $this->client = new Client;
        }

        return $this->client;
    }

    /**
     * Handles all requests to a web service
     * 
     * @param string $path
     * @param string $method Zend\Http\Request's Consts
     * @param array $params parameter for wedata
     * @return mixed
     * @throws Diggin\Service\Wedata\Exception
     */
    protected function makeRequest($path, $method, array $params = array())
    {
        $client = $this->getHttpClient();

        $client->resetParameters();

        $uri = UriFactory::factory(static::API_URL);
        $uri->setPath($path);

        if (is_array($params) && count($params) > 0) {
            if ($method == Request::METHOD_GET) {
                $client->setParameterGet($params);
            } elseif ($method == Request::METHOD_POST) {
                $client->setParameterPost($params);
            } else {
                $uri->setQuery($params);
            }
        }

        $client->setUri($uri);
        
        $response = $client->send();
        
        if (!$response->isSuccess()) {
             throw new Exception\UnexpectedValueException("Http client reported an error: '{$response->getMessage()}'");
        }
        
        //return response switching by Reqest Method
        if ($method == Request::METHOD_GET) {
            return $response->getBody();
        } else {
            $status = $response->getStatusCode();
            $headers = $response->headers();
            return array($status, $headers);
        }
    }

    public static function jsonDecode($json)
    {
        return Json::decode($json, Json::TYPE_OBJECT);
    }
}
