<?php
require_once 'Crawler.php';
require_once 'FindImagesTask.php';
require_once 'AverageResponseTimeTask.php';

// zend framework autoloading
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();


$startFrom = 'http://www.joshbutts.com/';

$crawler = new Crawler($startFrom);

if (file_exists('queue')) {
	$queue = unserialize(file_get_contents('queue'));
	$crawler->setQueue($queue);
}

$crawler->setDebugMode(true);
$crawler->registerTask(new AverageResponseTimeTask());

$crawler->run();

$queue = $crawler->getQueue();
file_put_contents('queue', serialize($queue));