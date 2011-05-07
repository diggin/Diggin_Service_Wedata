<?php
namespace Diggin\Service\Wedata;

use Zend\Loader;

class Item
{
    private $name;
    private $data;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getResourceUrl()
    {
    }

    public function setData($data)
    {
        $this->data = $data;
    }
                            

    public function getData()
    {}

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
