<?php
namespace Diggin\Service\Wedata;

interface Storage
{
    public function storeItems($database, $items);
    public function searchItem($database, $key, $term);
}
