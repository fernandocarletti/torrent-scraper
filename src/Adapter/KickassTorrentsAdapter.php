<?php

namespace Xurumelous\TorrentScraper\Adapter;

use Xurumelous\TorrentScraper\AdapterInterface;
use Xurumelous\TorrentScraper\Entity\SearchResult;
use Symfony\Component\DomCrawler\Crawler;

class KickassTorrentsAdapter implements AdapterInterface
{
    public function __construct(array $options = [])
    {

    }

    public function setHttpClient(\GuzzleHttp\Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getHttpClient()
    {
        return $this->httpClient;
    }

    public function search($query)
    {
        $response = $this->httpClient->get('http://kickass.so/usearch/' . urlencode($query) . '/?field=seeders&sorder=asc');
        $crawler = new Crawler((string) $response->getBody());
        $nodes = $crawler->filter('.torrentname');
        $results = [];

        foreach ($nodes as $node) {
            $result = new SearchResult();
            $nameCellCrawler = new Crawler($node->parentNode);
            $rowCrawler = new Crawler($node->parentNode->parentNode);
            $result->setName($nameCellCrawler->filter('.cellMainLink')->first()->text());
            $result->setSeeders((int) $rowCrawler->filter('td')->eq(4)->text());
            $result->setLeechers((int) $rowCrawler->filter('td')->eq(5)->text());
            $result->setTorrentUrl($nameCellCrawler->filter('.idownload')->last()->attr('href'));
            $result->setMagnetUrl($nameCellCrawler->filter('.imagnet')->first()->attr('href'));
            $results[] = $result;
        }

        return $results;
    }
}
