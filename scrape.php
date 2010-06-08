<?php
require_once 'Crawler.php';
require_once 'FindImagesTask.php';

// zend framework autoloading
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();


$startFrom = 'http://www.joshbutts.com/';

$crawler = new Crawler($startFrom);
$crawler->registerTask(new FindImagesTask());
$crawler->setDebugMode(true);
$crawler->run();
