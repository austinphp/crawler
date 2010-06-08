<?php
interface CrawlTaskInterface
{
    public function task($html, $cookies, $headers);
}