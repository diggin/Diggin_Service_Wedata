<?php

namespace Diggin\Service\Wedata;

interface Client
{
    public function getDatabases();
    public function getItems($database, $page = 1);
}
