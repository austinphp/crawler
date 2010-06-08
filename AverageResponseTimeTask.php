<?php
require_once 'CrawlTaskInterface.php';
class AverageResponseTimeTask implements CrawlTaskInterface
{
    protected $totalTime = 0;
    protected $totalPages = 0;
    
    public function task(Zend_Http_Response $response, Zend_Http_Client $client)
    {
        $timerStart = microtime(true);
        Zend_Debug::dump($client->getUri());
        $timerEnd = micotime(true);
        die;
    }
    
    public function shutdown()
    {
        echo "Average Response Time: " . ($this->totalTime / $this->totalPages) . " seconds\n";
    }
}