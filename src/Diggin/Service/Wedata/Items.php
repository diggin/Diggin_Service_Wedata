<?php
namespace Diggin\Service\Wedata;

use ArrayIterator,
    Diggin\Service\Wedata\Item;

class Items extends ArrayIterator
{
    public function current()
    {
        $stdObject = parent::current();
        return Item::fromObject($stdObject);
    }
}
