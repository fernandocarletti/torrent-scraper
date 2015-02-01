<?php

namespace Xurumelous\TorrentScraper;

trait HttpClientAware
{
    protected $httpClient;

    public function setHttpClient(\GuzzleHttp\Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getHttpClient()
    {
        return $this->httpClient;
    }
}