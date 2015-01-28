<?php

namespace Xurumelous\TorrentScraper;

interface AdapterInterface
{
    /**
     * Set the Guzzle client instance
     *
     * @param \GuzzleHttp\Client $httpClient
     */
    public function setHttpClient(\GuzzleHttp\Client $httpClient);

    /**
     * Get Guzzle client
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient();

    /**
     * Perform the search
     *
     * @param string $query
     */
    public function search($query);
}
