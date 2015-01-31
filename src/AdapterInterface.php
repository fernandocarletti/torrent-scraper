<?php

namespace Xurumelous\TorrentScraper;

interface AdapterInterface
{
    /**
     * Construct the adapter with its options.
     *
     * @param array $options
     */
    public function __construct(array $options);

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
