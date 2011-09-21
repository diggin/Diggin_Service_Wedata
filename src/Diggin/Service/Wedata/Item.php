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

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {}

    // getDataMapper
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

    public function retrieveId()
    {
        $resourceUrl = $this->getResourceUrl();
        return ;//末尾;
    }

    public function retrieveDatabase()
    {
    }

    protected function loadClassFile($databaseName)
    {
        return Loader::loadFile($databaseName, __DIR__.'/ItemData/');
    }
}
