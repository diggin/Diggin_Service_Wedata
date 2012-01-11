<?php

/**
 * @namespace
 */
namespace Diggin\Service\Wedata;

/**
 * Service Api interface of Wedata
 *
 * @see http://wedata.net/help/api
 */
interface Api
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
    public function getDatabases($page = 1);
    public function getDatabase($databaseName = null, $page = null);
    public function createDatabase(array $params = array());
    public function udpateDatabase(array $params = null, $databaseName = null);

    // @apikey 
    public function deleteDatabase($databaseName = null);

    public function getItems($database, $page = 1);
    public function getItem($itemId, $page = null);
    public function insertItem(array $params = array(), $databaseName = null);

    // @apikey
    public function updateItem($itemId, array $params = array());

    //@apikey
    public function deleteItem($itemId);
    */
}
