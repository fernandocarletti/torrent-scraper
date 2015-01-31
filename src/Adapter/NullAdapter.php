<?php

namespace Xurumelous\TorrentScraper\Adapter;

use Xurumelous\TorrentScraper\AdapterInterface;
use Xurumelous\TorrentScraper\Entity\SearchResult;
use Symfony\Component\DomCrawler\Crawler;

class NullAdapter implements AdapterInterface
{
    public function __construct(array $options = [])
    {
    }

    public function setHttpClient(\GuzzleHttp\Client $httpClient)
    {
    }

    public function getHttpClient()
    {
    }

    public function search($query)
    {
       return [];
    }
}
