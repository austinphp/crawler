<?php
class Queue
{
    protected $alreadyVisited = array();
    protected $toVisit = array();
    
    public function push($url)
    {
        if (!isset($this->alreadyVisited[$url])) {
            $this->toVisit[] = $url;
            $this->alreadyVisited[$url] = true;
        }
    }
    
    public function isEmpty()
    {
        return count($this->toVisit) == 0;
    }
    
    public function queueLength()
    {
        return count($this->toVisit);
    }
    
    public function pop()
    {
        return array_pop($this->toVisit);
    }
}