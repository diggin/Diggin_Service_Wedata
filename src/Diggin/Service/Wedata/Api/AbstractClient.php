<?php
namespace Diggin\Service\Wedata\Api;

use Diggin\Service\Wedata\Api,
    Diggin\Service\Wedata\Databases,
    Diggin\Service\Wedata\Database,
    Diggin\Service\Wedata\Items,
    Diggin\Service\Wedata\Item,
    Diggin\Service\Wedata\Exception;
    // MissingApiKeyException extends \LogicException

/**
 * Wedata Service API Client
 */
abstract class AbstractClient implements Api
{
    protected $apiKey;

    public function __construct($api_key = null)
    {
        $this->apiKey = $api_key;
    }
    
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
            throw new Exception\InvalidArgumentException("currently parameter not set 'page'");
        }
        $responseBody = $this->makeRequest(static::PATH_GET_DATABASES, 'GET', $params);
        
        return new Databases(static::jsonDecode($responseBody));
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
            throw new Exception\InvalidArgumentException();
        }
        
        $path = sprintf(static::PATH_GET_DATABASE, rawurlencode($databaseName));
        $responseBody = $this->makeRequest($path, 'GET');

        return new Database(static::jsonDecode($responseBody));
    }

    /**
     * @param mixed array|Database
     */
    public function createDatabase($database)
    {
        $params = array();

        if ($this->apiKey) {
            $params[static::KEY_APIKEY] = $this->apiKey;
        } else {
            throw new Exception\MissingApiKeyException('API key is not set ');
        }

        if ($database instanceof Database) {
            $database = $database->toApiArray();
        }

        if (is_array($database)) {
            $params['database'] = $database;
        } else {
            throw new Exception();
        }

        if (!isset($params['database']['name'])) {
            throw new Exception\InvalidArgumentException('Database name is not set ');
        } elseif (!isset($params['database']['required_keys'])) {
            throw new Exception\InvalidArgumentException('required_keys is not set');
        }

        $result = $this->makeRequest(static::PATH_CREATE_DATABASE, 'POST', $params);

        return $result;
    }

    /**
     * $api->updateDatabase(new Database(array('name' => 'foo' ,'required_keys' => 'bar baz')));
     * OR
     * $api->updateDatabase('fooDatabase', array('required_keys' => 'bar baz'));
     */
    public function udpateDatabase($database, $databaseParams = array())
    {
        $params = array();

        if ($this->apiKey) {
            $params[static::KEY_APIKEY] = $this->apiKey;
        } else {
            throw new Exception\MissingApiKeyException('API key is not set ');
        }

        if ($database instanceof Database) {
            $databaseName = $database->getName();
            $params['database'] = $database->toApiArray() + $databaseParams;
        } else if (is_sting($database) && is_array($databaseParams)) {
            $databaseName = $database;
            $params['database'] = $databaseParams;
        } else {            
            throw new Exception\InvalidArgumentException('$database should be '.__NAMESPACE__.'\\Database object or string');
        }

        if (!isset($params['database']['required_keys'])) {
            throw new Exception\InvalidArgumentException('required_keys is not set');
        }

        $path = sprintf(static::PATH_UPDATE_DATABASE, rawurlencode($databaseName));
        $return = $this->makeRequest($path, 'PUT', $params);

        return $return;
    }

    /** @apikey */
    public function deleteDatabase($database)
    {
        $params = array();

        if ($this->apiKey) {
            $params[static::KEY_APIKEY] = $this->apiKey;
        } else {
            throw new Exception\MissingApiKeyException('API key is not set ');
        }

        if ($database instanceof Database) {
            $databaseName = $database->getName();
        } else if (is_sting($database)) {
            $databaseName = $database;
        } else {            
            throw new Exception\InvalidArgumentException('$database should be '.__NAMESPACE__.'\\Database object or string');
        }

        $path = sprintf(self::PATH_DELETE_DATABASE, rawurlencode($databaseName));
        $return = $this->makeRequest($path, 'DELETE', $params);

        return $return;    
    }

    //////

    public function getItems($database, $page = 1)
    {
        if ($database instanceof Database) {
            $database = $database->getName();
        }

        if (!is_string($database)) {
            throw new Exception\InvalidArgumentException('$database should be '.__NAMESPACE__.'\\Database object or string');
        }

        if ($page === null) {
            $params = array();
        } else if (is_numeric($page)) {
            $params = array(static::KEY_PAGE => $page);
        } else {
            throw new Exception\InvalidArgumentException("currently parameter not set 'page'");
        }
        
        $path = sprintf(static::PATH_GET_ITEMS, rawurlencode($database));
        $responseBody = $this->makeRequest($path, 'GET', $params);

        return new Items(static::jsonDecode($responseBody));
    }

    public function getItem($itemId)
    {
        if (!is_numeric($itemId)) {
            throw new Exception\InvalidArgumentException("itemId shoud be numeric");
        }

        $path = sprintf(static::PATH_GET_ITEM, $itemId);
        $responseBody = $this->makeRequest($path, 'GET');

        return Item::fromObject(static::jsonDecode($responseBody));
    }

    /**
     * @apikey
     */
    public function insertItem($databaseName, $item, $name = null)
    {
        $params = array();
        if ($this->apiKey) {
            $params[static::KEY_APIKEY] = $this->apiKey;
        } else {
            throw new Exception\MissingApiKeyException('API key is not set ');
        }

        if ($item instanceof Item) {
            if ($name || ($name = $item->getName())) $params['name'] = $name;
            $params['data'] = $item->getData();
        } else if (is_array($item)) {
            if ($item) $params['name'] = $name;
            $params['data'] = $item;
        }

        if (is_array($item)) {
            throw new Exception\InvalidArgumentException('$item require Item Object or Item array');
        }

        $path = sprintf(static::PATH_CREATE_ITEM, rawurlencode($databaseName));
        return $this->makeRequest($path, 'POST', $item);
    }

    /** @apikey */
    // $itemId, array $params = array())
    // Item
    public function updateItem($item, array $data = array())
    {
        if ($item instanceof Item) {
            $itemId = $item->retrieveId();
            $params['data'] = $item->getData() + $data;
        } else if (is_string($itemId)) {
            $itemId = $item;
            $params['data'] = $data;
        } else {
            throw new Exception\InvalidArgumentException('$item should be'.__NAMESPACE__.'\\Item object or string');
        }

        $path = sprintf(static::PATH_UPDATE_ITEM, $itemId);
        return $this->makeRequest($path, 'PUT', $params);
    }

    /** @apikey */
    public function deleteItem($item)
    {
        if ($this->apiKey) {
            $params[static::KEY_APIKEY] = $this->apiKey;
        } else {
            throw new Exception\MissingApiKeyException('API key is not set ');
        }

        if ($item instanceof Item) {
            $itemId = $item->retrieveId();
        } else if (is_string($itemId)) {
            $itemId = $item;
        } else {
            throw new Exception\InvalidArgumentException('$item should be'.__NAMESPACE__.'\\Item object or string');
        }
        
        $path = sprintf(static::PATH_DELETE_ITEM, $itemId);
        return $this->makeRequest($path, 'DELETE', $params);
    }

    /**
     * Handles all requests to a web service
     * 
     * @param string $path
     * @param string $method (Zend\Http\Request's Consts)
     * @param array $params parameter for wedata
     * @return mixed
     * @throws Diggin\Service\Wedata\Exception
     */
    abstract protected function makeRequest($path, $method, array $params = array());

    public static function jsonDecode($json)
    {
        return json_decode($json);
    }
}

