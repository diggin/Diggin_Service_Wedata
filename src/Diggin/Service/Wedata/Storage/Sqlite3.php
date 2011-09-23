<?php

namespace Diggin\Service\Wedata\Storage\Adapter;
use Diggin\Service\Wedata\Storage\Adapter,
    SQLite3 as SQLite3Obj;

class Sqlite3 implements Adapter
{
    private $sqlite3;

    public function __construct(SQLite3Obj $sqlite3)
    {
        $this->sqlite3 = $sqlite3;
    }

    public function storeItems($database, $items)
    {
    
        $this->insertItemTable($database, $id, $name);

        // target ItemData Table is available?
        $this->insertItemDataTable($database, $id,$array);
    }

    /**
     * search by each item's name
     */
    public function searchItem($database, $name)
    {
    
    }

    public function searchItemData($database, $key, $term)
    {}

    /////

    public function createDatabaseTable()
    {
        $query = <<<CREATEDATABASE
CREAT TABLE DATABASE 
(name text,
 updated_at text
CREATEDATABASE;
        $this->sqlite3->query($query);
    }

    public function createItemTable()
    {
        $query = <<<CREATEITEM
CREAT TABLE item
(database text,
 id int,
 name text,
 resource_url text
)
CREATEITEM;

        $this->sqlite3->query($query);
    }

    // @todo
    public function createItemDataTable($itemData)
    {
        // create CREATE SQL from itemData
        // e.g. (item_id int, nextLink text

        $databaseName = get_class($itemData);

        $query = <<<ITEMENTITY
CREAT TABLE item_$databaseName
(
ITEMENTITY;
    }

}
