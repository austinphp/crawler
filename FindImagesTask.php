<?php
require_once 'CrawlTaskInterface.php';
class FindImagesTask implements CrawlTaskInterface
{
    protected $images = array();
    
    public function task(Zend_Http_Response $response)
    {
        $query = new Zend_Dom_Query($response->getBody());
        $images = $query->query('img');
        foreach ($images as $image) {
            $this->images[] = $image->getAttribute('src');
        }
        $this->images = array_unique($this->images);
    }
    
    public function shutdown()
    {
        foreach ($this->images as $image) {
            echo $image . "\n";
        }
    }
}