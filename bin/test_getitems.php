<?php
//autoload zf2
require_once dirname(__DIR__).'/_autoload.php';

use Diggin\Service\Wedata\Api\Client;

$client = new Client;
$items = $client->getItems('AutoPagerize');

var_dump(iterator_to_array($items));
