<?php
//autoload zf2
require_once dirname(__DIR__).'/_autoload.php';
require_once 'client.php';

if (!$responseString = @file_get_contents($path = dirname(__DIR__).'/tmp/getItems.dat')) {
    echo 'require response at '.$path;
    die();
}

use Diggin\Service\Wedata\Client\MockClient,
    Diggin\Service\Wedata\EntityGenerator;

$response = Zend\Http\Response::fromString($responseString);

$client = new MockClient;
$items = $client->getItems('AutoPagerize', 1, $response);

var_dump(iterator_to_array($items));
