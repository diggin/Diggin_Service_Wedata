<?php
// only tests & demo

$zf2 = $_SERVER['HOME'].'/dev/zendframework_zf2/library';
$library = __DIR__.'/src';

require_once $zf2.'/Zend/Loader/StandardAutoloader.php';
$loader = new Zend\Loader\StandardAutoloader;
$loader->registerNamespace('Diggin', $library.'/Diggin');
$loader->register();
