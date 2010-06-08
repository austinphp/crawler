<?php
interface CrawlTaskInterface
{
    function task(Zend_Http_Response $response, Zend_http_Client $client);
    function shutdown();
}