<?php
require_once 'CrawlTaskInterface.php';
class AverageResponseTimeTask implements CrawlTaskInterface
{
    protected $totalTime = 0;
    protected $totalPages = 0;
    
    public function task(Zend_Http_Response $response, Zend_Http_Client $client)
    {
        $timerStart = microtime(true);
        get_headers($client->getUri());
        $timerEnd = microtime(true);
        $this->totalPages++;
        $this->totalTime += ($timerEnd - $timerStart);
    }
    
    public function shutdown()
    {
        echo "Average Response Time: " . ($this->totalTime / $this->totalPages) . " seconds\n";
    }
} 