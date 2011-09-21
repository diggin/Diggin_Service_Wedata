<?php
namespace Diggin\Service\Wedata\Storage;

interface Adapter
{
    public function storeItems($database, $items);
    public function searchItem($database, $key, $term);
}
