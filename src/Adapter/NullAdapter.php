<?php

declare(strict_types=1);

namespace Xurumelous\TorrentScraper\Adapter;

use Xurumelous\TorrentScraper\AdapterInterface;

class NullAdapter implements AdapterInterface
{
    public function __construct(array $options = [])
    {
    }

    public function setHttpClient(\GuzzleHttp\Client $httpClient): void
    {
    }

    public function getHttpClient(): void
    {
    }

    public function search($query)
    {
        return [];
    }
}
