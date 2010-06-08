<?php
require_once 'Crawler.php';
require_once 'FindImagesTask.php';
require_once 'AverageResponseTimeTask.php';

// zend framework autoloading
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();


$startFrom = 'http://www.vertive.com/';

$crawler = new Crawler($startFrom);
$crawler->registerTask(new FindImagesTask());
$crawler->registerTask(new AverageResponseTimeTask());
$crawler->setDebugMode(true);
$crawler->run();
