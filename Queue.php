<?php
class Queue
{
    protected $alreadyVisited = array();
    protected $toVisit = array();
    
    public function push($url)
    {
        if (!in_array($url, $this->alreadyVisited)) {
            $this->toVisit[] = $url;
            $this->alreadyVisited[] = $url;
        }
    }
    
    public function isEmpty()
    {
        return count($this->toVisit) == 0;
    }
    
    public function pop()
    {
        return array_pop($this->toVisit);
    }
}