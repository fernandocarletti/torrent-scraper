<?php

declare(strict_types=1);

namespace Xurumelous\TorrentScraper;

use GuzzleHttp\Client;

trait HttpClientAware
{
    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @param Client $httpClient
     */
    public function setHttpClient(Client $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
}
