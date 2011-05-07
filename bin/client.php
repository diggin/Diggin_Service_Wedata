<?php
//autoload zf2
require_once dirname(__DIR__).'/_autoload.php';

use Zend\Uri\Url;

class Wedata
{
    const API_URL = 'http://wedata.net';
    
    //parameter keys
    const KEY_APIKEY = 'api_key';
    const KEY_PAGE = 'page';
    const KEY_DATABASE = 'database';
    
    // path to acces database
    const PATH_GET_DATABASES = '/databases.json';
    const PATH_GET_DATABASE  = '/databases/%s.json';
    const PATH_CREATE_DATABASE = '/databases';
    const PATH_UPDATE_DATABASE = '/databases/%s';
    const PATH_DELETE_DATABASE = '/databases/%s';
    
    // path to acces item
    const PATH_GET_ITEMS = '/databases/%s/items.json';//dbname
    const PATH_GET_ITEM  = '/items/%s.json'; //item id
    const PATH_CREATE_ITEM = '/databases/%s/items'; //dbname
    const PATH_UPDATE_ITEM = '/items/%s'; //item id
    const PATH_DELETE_ITEM = '/items/%s'; //item id

    /**
     * Zend_Http_Client Object
     *
     * @var Zend_Http_Client
     */
    protected $_httpClient;

    /**
     * Request parameters
     *
     * @var array
     */
    protected $_params = array();

    /**
     * Decode Type to handle Wedata's response
     *
     * @var int|null
     */
    protected $_decodetype = null;

    /**
     * Constructs a new Wedata Web Service Client
     *
     * @param array $params parameter acording Wedata
     * @param boolean | string @see Zend_Json
     * @return null
     */
    public function __construct(array $params = null, $decodetype = null)
    {
        $this->_params = $params;
        $this->_decodetype = $decodetype;
    }
 
    /**
     * Set Zend_Http_Client
     *
     * @param Zend_Http_Client $client
     */   
    public function setHttpClient(\Zend\Http\Client $client)
    {
        $this->_httpClient = $client;
    }

    /**
     * Get Http Client - lazy load
     *
     * @return Zend_Http_Client
     */
    public function getHttpClient()
    {
        if (!$this->_httpClient instanceof \Zend\Http\Client) {
            $this->_httpClient = new \Zend\Http\Client();
        }

        return $this->_httpClient;
    }

    /**
     * Decode Json Value
     *
     * @param string $value json
     * @return mixed decoded json's value
     */
    protected function _decode($value)
    {
        if ($this->_decodetype === false) {
            //nothing to do
        } else {
            if ($this->_decodetype === null || $this->_decodetype === \Zend\Json\Json::TYPE_ARRAY) {
                $value = \Zend\Json\Json::decode($value, \Zend\Json\Json::TYPE_ARRAY);
            } else {
                $value = \Zend\Json\Json::decode($value, $this->_decodetype);
            }    
        }
        
        return $value;
    }
    
    /**
     * Retrieve current object's parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * Retrieve param by key
     *
     * @param string $key
     * @return mixed|null Null when not found
     */
    public function getParam($key)
    {
        if (array_key_exists($key, $this->_params)){
            return $this->_params[$key];
        }

        return null;
    }
    
    /**
     * setting parameter
     * 
     * @param array $params
     */
    public function setParams(array $params)
    {
        foreach ($params as $key => $value){
            $this->_params[strtolower($key)] = $value;
        }
    }
    
    /**
     * adding parameter
     * 
     * @param string
     * @param string
     */
    public function setParam($key, $value)
    {
        $this->_params[$key] = $value;
    }
    
    /**
     * adding DATABASE's parameter
     * 
     * @param string $key
     * @param string $value
     */
    public function setParamDatabase($key, $value)
    {
        $this->_params['database'][$key] = $value;
    }

    /**
     * Set Database's name
     *
     * @param string $databaseName
     */    
    public function setDatabaseName($databaseName)
    {
        $this->_params['database']['name'] = $databaseName;
    }

    /**
     * Get Currently database's name
     *
     * @throws Diggin_Service_Exception
     */
    public function getDatabaseName()
    {
        if (isset($this->_params['database']['name'])) {
            return $this->_params['database']['name'];
        }

        // require_once 'Diggin/Service/Exception.php';
        throw new \Exception('database name is not set');
    }

    /**
     * Handles all requests to a web service
     * 
     * @param string path
     * @param string Prease,using Zend_Http_Client's const
     * @param array parameter for wedata
     * @return mixed
     * @throws Diggin_Service_Wedata_Exception
     */
    protected function makeRequest($path, $method, array $params = array())
    {
        $client = $this->getHttpClient();

        $client->resetParameters();

        $uri = new Url(self::API_URL);
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
             /**
              * @see Diggin_Service_Exception
              */
             // require_once 'Diggin/Service/Exception.php';
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
    
    public function getDatabases()
    {
        $responseBody = $this->makeRequest(self::PATH_GET_DATABASES, \Zend\Http\Client::GET);
        
        return $this->_decode($responseBody);
    }

    public function getDatabase($databaseName = null, $page = null)
    {
        $databaseName = (isset($databaseName)) ? $databaseName : $this->getDatabaseName();

        if ($page or ($page = $this->getParam(self::KEY_PAGE))) {
            $params = array(self::KEY_PAGE => $page);
        } else {
            // require_once 'Diggin/Service/Exception.php';
            throw new Exception("currently parameter not set 'page'");
        }
        
        $path = sprintf(self::PATH_GET_DATABASE, rawurlencode($databaseName));
        $responseBody = $this->makeRequest($path, \Zend\Http\Client::GET, $params);
        
        return $this->_decode($responseBody);
    }

    public function createDatabase(array $params = array())
    {
        $params = (isset($params)) ? $params : $this->getParams();
        
        if (!isset($params['api_key'])){
            // require_once 'Diggin/Service/Exception.php';
            throw new Exception('API key is not set ');
        } elseif (!isset($params['database']['name'])) {
            // require_once 'Diggin/Service/Exception.php';
            throw new Exception('Database name is not set ');
        } elseif (!isset($params['database']['required_keys'])) {
            // require_once 'Diggin/Service/Exception.php';
            throw new Exception('required_keys is not set');
        }
        
        $return = $this->makeRequest($this->PATH_CREATE_DATABASE, \Zend\Http\Client::POST, $params);
        
        return $return;
    }
    
    public function udpateDatabase(array $params = null, $databaseName = null)
    {
        $databaseName = (isset($databaseName)) ? $databaseName : $this->getDatabaseName();
        $params (isset($params)) ? $params : $this->getParams();
        
        if(!isset($params['api_key'])){
            // require_once 'Diggin/Service/Exception.php';
            throw new Exception('API key is not set ');
        } elseif (!isset($params['database']['required_keys'])) {
            // require_once 'Diggin/Service/Exception.php';
            throw new Exception('required_keys is not set');
        }

        $path = sprintf(self::PATH_UPDATE_DATABASE, rawurlencode($databaseName));
        $return = $this->makeRequest($path, \Zend\Http\Client::PUT, $params);
        
        return $return;
    }
    
    public function deleteDatabase($databaseName = null, $apiKey = null)
    {
        $databaseName = (isset($databaseName)) ? $databaseName : $this->getDatabaseName();
        $params = isset($apiKey) ? array(self::KEY_APIKEY => $apiKey) : $this->getParams();
        
        if (!isset($params[self::KEY_APIKEY])) {
            // require_once 'Diggin/Service/Exception.php';
            throw new Exception('API key is not set ');
        }
        
        $path = sprintf(self::PATH_DELETE_DATABASE, rawurlencode($databaseName));
        $return = $this->makeRequest($path, \Zend\Http\Client::DELETE, $params);
        
        return $return;
    }
    
    //////item methods    
    public function getItems($page = null, $databaseName = null)
    {
        $databaseName = (isset($databaseName)) ? $databaseName : $this->getDatabaseName();

        if (isset($page)) {
            $params = array(self::KEY_PAGE => $page); 
        } else if (!$this->getParam(self::KEY_PAGE)) {
            $params = array();
        } else {
            $params = array(self::KEY_PAGE => $this->getParam(self::KEY_PAGE));
        }
        
        $path = sprintf(self::PATH_GET_ITEMS, rawurlencode($databaseName));
        $responseBody = $this->makeRequest($path, \Zend\Http\Client::GET, $params);
        
        return $this->_decode($responseBody);
    }

    /**
     * Get Item
     * 
     * @param string $itemId
     * @param string $page
     * @return array Decorded Result
     */
    public function getItem($itemId, $page = null)
    {
        //@todo if int set as itemid or string searching itemid by name
        //is_integer($item);
        //is_string($item) ;
        
        $page = isset($page) ? $page : $this->getParam(self::KEY_PAGE);
        
        if ($page) {
            $params = array(self::KEY_PAGE => $page);
        } else {
            $params = array();
        }

        $path = sprintf(self::PATH_GET_ITEM, $itemId);
        $responseBody = $this->makeRequest($path, \Zend\Http\Client::GET, $params);
        
        return $this->_decode($responseBody);
    }
    
    public function insertItem(array $params = array(), $databaseName = null)
    {
        $databaseName = (isset($databaseName)) ? $databaseName : $this->getDatabaseName();
        
        $path = sprintf(self::PATH_CREATE_ITEM, rawurlencode($databaseName));
        $return = $this->makeRequest($path, \Zend\Http\Client::POST, $params);
        
        return $return;
    }
    
    public function updateItem($itemId, array $params = array())
    {
        if (!isset($params['api_key'])) {
            // require_once 'Diggin/Service/Exception.php';
            throw new Exception('API key is not set ');
        }
        
        $path = sprintf(self::PATH_UPDATE_ITEM, $itemId);
        $return = $this->makeRequest($path, \Zend\Http\Client::PUT, $params);
        
        return $return;
    }
    
    public function deleteItem($itemId, $apiKey = null)
    {
        $apiKey = isset($apiKey) ? $apiKey : $this->getParam(self::KEY_APIKEY);
        
        if ($apikey) {
            $params = array('api_key' => $apiKey);
        } else {
            // require_once 'Diggin/Service/Exception.php';
            throw new Exception('API key is not set ');
        }
        
        $path = sprintf(self::PATH_DELETE_ITEM, $itemId);
        $return = $this->makeRequest($path, \Zend\Http\Client::DELETE, $params);
        
        return $return;
    }

}
