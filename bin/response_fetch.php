<?php
//autoload zf2
require_once dirname(__DIR__).'/_autoload.php';
require_once 'client.php';

if (!isset($argv[1])) {
    $ref = new ReflectionClass('Wedata');
    echo 'Available methods..';
    foreach ($ref->getMethods() as $r) echo $r->name, PHP_EOL;
    die;
}

$method = $argv[1];

$wedata = new Wedata;
//$wedata->$method();

$arguments = $argv;
array_shift($arguments);
array_shift($arguments);
call_user_func_array(array($wedata, $method), $arguments);

//push last reponse
$response = $wedata->getHttpClient()->getLastResponse();

$output = dirname(__DIR__).'/tmp/'.$method.'.dat';
file_put_contents($output, (string)$response);
echo "push file to $output";

