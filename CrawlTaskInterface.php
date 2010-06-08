<?php
interface CrawlTaskInterface
{
    function task(Zend_Http_Response $response);
    function shutdown();
}