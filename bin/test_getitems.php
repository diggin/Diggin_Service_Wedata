<?php
//autoload zf2
require_once dirname(__DIR__).'/vendor/autoload.php';

use Diggin\Service\Wedata\Api\ZF2Client as Client;

$client = new Client;
$items = $client->getItems('AutoPagerize');

var_dump(iterator_to_array($items));
