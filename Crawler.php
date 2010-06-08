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
        if ($this->debugMode) {
            echo "Restricting crawl to $this->domain\n";
        }
        while (!$this->queue->isEmpty()) {
        	
        	//get a new url to crawl
            $url = $this->queue->pop();
            
            if ($this->debugMode) {
                echo "Queue Length: " . $this->queue->queueLength() . "\n";
                echo "Crawling " . $url . "\n";
            }
            
            //set the url into the http client
            $this->client->setUri($url);
            
            //make the request to the remote server
            $this->currentResponse = $this->client->request();
            
            //don't bother trying to parse this if it's not text
            if (stripos($this->currentResponse->getHeader('Content-type'), 'text') === false) {
            	continue;
            }
            
            //search for <a> tags in the document
            $body = $this->currentResponse->getBody();
            $linksQuery = new Zend_Dom_Query($body);
            $links = $linksQuery->query('a');
            
            if ($this->debugMode) {
                echo "\tFound " . count($links) . " links...\n";
            }
            
            foreach ($links as $link) {
            	
            	//get the href of the link and find out if it links to the current host
                $href = $link->getAttribute('href');
                $urlparts = parse_url($href);

                if ($this->stayOnDomain && isset($urlparts["host"]) && $urlparts["host"] != $this->domain) {
                    continue;
                }
                
                //if it's an absolute link without a domain or a scheme, attempt to fix it
                if (!isset($urlparts["host"])) {
                    $href = 'http://' . $this->domain . $href;  //this is a really naive way of doing this!
                }
                
                //push this link into the queue to be crawled
                $this->queue->push($href);
            }
            
            //for each page that we see, run every registered task across it
            foreach ($this->tasks as $task) {
                $task->task($this->currentResponse, $this->client);
            }
        }
        
        //after we're done with everything, call the shutdown hook on all the tasks
        $this->shutdownTasks();
    }
    
    public function shutdownTasks()
    {
        foreach  ($this->tasks as $task) {
            $task->shutdown();
        }
    }
}