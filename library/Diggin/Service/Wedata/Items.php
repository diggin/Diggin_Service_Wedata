<?php
namespace Diggin\Service\Wedata;

use ArrayIterator,
    Diggin\Service\Wedata\Item;

class Items extends ArrayIterator
{
    public function current()
    {
        $stdObject = parent::current();
        $item = new Item();
        $item->setName($stdObject->name);
        $item->setData($stdObject->data);

        return $item;
    }
}
