<?php
include __DIR__.'/bootstrap.php';

use Diggin\Service\Wedata\Api\ZF2Client as Client;

$client = new Client;
$items = $client->getItems('AutoPagerize', null);

file_put_contents(TESTS_DIGGIN_SERVICE_WEDATA_DATADIR.'/autopagerize_items.dat', serialize($items));
