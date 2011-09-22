<?php
namespace Diggin\Service\Wedata\Client;

use Diggin\Service\Wedata\Client,
    Diggin\Service\Wedata\Databases,
    Diggin\Service\Wedata\Database,
    Diggin\Service\Wedata\Items,
    Diggin\Service\Wedata\Item,
    Zend\Json\Json,
    Zend\Uri\UriFactory,
    Zend\Http\Request,
    Zend\Service\AbstractService;

/**
 * Wedata Service API Client
 */
class ServiceClient extends AbstractService implements Client
{
    /**
     * Get Databases
     *
     * @param int $page
     * @return Diggin\Service\Wedata\Databases
     */
    public function getDatabases($page = 1)
    {
        if ($page === null) {
            $params = array();
        } else if (is_numeric($page)) {
            $params = array(static::KEY_PAGE => $page);
        } else {
            throw new Exception("currently parameter not set 'page'");
        }
        $responseBody = $this->makeRequest(static::PATH_GET_DATABASES, Request::METHOD_GET, $params);
        
        return new Databases(Json::decode($responseBody, Json::TYPE_OBJECT));
    }

    /**
     * Describe database information
     *
     * @param mixed string|Database
     * @param int
     * @return Diggin\Service\Wedata\Database
     */
    public function getDatabase($databaseName)
    {
        if (!is_string($databaseName)) {
            throw new UnexcpetedValueException();
        }
        
        $path = sprintf(static::PATH_GET_DATABASE, rawurlencode($databaseName));
        $responseBody = $this->makeRequest($path, Request::METHOD_GET);

        return new Database(Json::decode($responseBody, Json::TYPE_OBJECT));
    }

    public function getItems($database, $page = 1)
    {
        if ($database instanceof Database) {
            $database = $database->getName();
        }

        if (!is_string($database)) {
            throw new UnexcpetedValueException();
        }

        if (is_numeric($page)) {
            $params = array(static::KEY_PAGE => $page);
        } else {
            throw new Exception("currently parameter not set 'page'");
        }
        
        $path = sprintf(static::PATH_GET_ITEMS, rawurlencode($database));
        $responseBody = $this->makeRequest($path, Request::METHOD_GET, $params);

        return new Items(Json::decode($responseBody, Json::TYPE_OBJECT));
    }

    public function getItem($itemId /*, $dataMapperManager */)
    {
        if (!is_numeric($itemId)) {
            throw new Exception("");
        }

        $path = sprintf(static::PATH_GET_ITEM, $itemId);
        $responseBody = $this->makeRequest($path, Request::METHOD_GET);

        return Item::fromObject(Json::decode($responseBody, Json::TYPE_OBJECT));
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
             throw new Exception("Http client reported an error: '{$response->getMessage()}'");
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
}
