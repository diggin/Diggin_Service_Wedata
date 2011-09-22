<?php
namespace Diggin\Service\Wedata;

use Zend\Loader;

class Item
{
    private $name;
    private $resource_url;
    private $updated_at;
    private $created_by;
    private $database_resource_url;
    private $data;
    private $created_at;

    public static function fromObject(\stdClass $data)
    {
        $item = new static;
        $item->setName($data->name);
        $item->setResourceUrl($data->resource_url);
        $item->setUpdatedAt($data->updated_at);
        $item->setCreatedBy($data->created_by);
        $item->setDatabaseResourceUrl($data->database_resource_url);
        $item->setData($data->data);
        $item->setCreatedAt($data->created_at);

        return $item;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setResourceUrl($url)
    {
        $this->resource_url = $url;
    }

    public function getResourceUrl()
    {
        return $this->resource_url;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;
    }

    public function getCreatedBy()
    {
        return $this->created_by;
    }

    public function setDatabaseResourceUrl($database_resource_url)
    {
        $this->database_resource_url = $database_resource_url;
    }

    public function getDatabaseResourceUrl()
    {
        return $this->database_resource_url;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function retrieveId()
    {
        $resourceUrl = $this->getResourceUrl();
        preg_match('#items/([0-9]+)$#', $resourceUrl, $m);
        return $m[1];
    }

    public function retrieveDatabaseName()
    {
        $resourceUrl = $this->getDatabaseResourceUrl();
        preg_match('#(?:databases/)(.*)$#', $resourceUrl, $m);
        return $m[1];
    }

    // @todo
    public function getDataEntity()
    {
        $databaseName = $this->retrieveDatabase();
        //filter

        $this->loadClassFile($databaseName);

        $itemData = new $databaseName;
        foreach ((array)$this->data as $key => $var) {
            $setter = 'set'.ucfirst($key);
            $itemData->$setter($var);
        }
        $itemData->setId($this->retrieveId());

        return $itemData;
    }

    protected function loadClassFile($databaseName)
    {
        return Loader::loadFile($databaseName, __DIR__.'/ItemData/');
    }
}
