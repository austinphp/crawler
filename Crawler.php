<?php
require_once 'Queue.php';

class Crawler
{
    protected $queue;
    protected $stayOnDomain = true;
    protected $tasks = array();
    protected $debugMode;
    protected $domain;
    
    /**
     * @var Zend_Http_Response
     */
    protected $currentResponse;
    
    /**
     * @var Zend_Http_Client
     */
    protected $client;
    
    public function __construct($startFrom)
    {
        $this->queue = new Queue();
        $this->queue->push($startFrom);    
        $this->client = new Zend_Http_Client();
        $this->domain = parse_url($startFrom, PHP_URL_HOST);
    }
    
    public function registerTask(CrawlTaskInterface $crawlTask)
    {
       $this->tasks[] = $crawlTask;
    }
    
    public function setDebugMode($val)
    {
        $this->debugMode = $val;
    }
    
    public function run()
    {
        while (!$this->queue->isEmpty()) {
            $url = $this->queue->pop();
            
            if ($this->debugMode) {
                echo "Queue Length: " . $this->queue->queueLength() . "\n";
                echo "Crawling " . $url . "\n";
            }
            
            $this->client->setUri($url);
            $this->currentResponse = $this->client->request();
            
            $body = $this->currentResponse->getBody();
            $linksQuery = new Zend_Dom_Query($body);
            $links = $linksQuery->query('a');
            
            if ($this->debugMode) {
                echo "\tFound " . count($links) . " links...\n";
            }
            
            foreach ($links as $link) {
                $href = $link->getAttribute('href');
                $urlparts = parse_url($href);
                if ($this->stayOnDomain && $urlparts["host"] != $this->domain) {
                    continue;
                }
                $this->queue->push($href);
            }
            foreach ($this->tasks as $task) {
                $task->task($this->currentResponse);
            }
        }
        $this->shutdownTasks();
    }
    
    public function shutdownTasks()
    {
        foreach  ($this->tasks as $task) {
            $task->shutdown();
        }
    }
}