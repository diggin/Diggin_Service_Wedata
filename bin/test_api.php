<?php
//autoload zf2
require_once dirname(__DIR__).'/_autoload.php';

use Diggin\Service\Wedata\Api\Client;

$ref = new ReflectionClass('Diggin\\Service\\Wedata\\Api');
if (!isset($argv[1])) {
    echo 'Available methods..', PHP_EOL;
    foreach ($ref->getMethods() as $r) echo $r->name, PHP_EOL;
    die;
} else {
    $methods = array();
    foreach ($ref->getMethods() as $v) {
        $methods[] = $v->name;
    }

    if (!in_array($argv[1], $methods)) {
        die($argv[1] .' is invalid');
    }

    $method = $argv[1];
}

array_shift($argv); //this script 
array_shift($argv); //methods

// filter numeric to int
$new_args = array();
foreach ($argv as $v) {
    if (is_numeric($v)) {
        $new_args[] = (int) $v;
    } else {
        $new_args[] = $v;
    }
}

$client = new Client;
$ret = call_user_func_array(array($client, $method), $new_args);
var_dump($ret);
