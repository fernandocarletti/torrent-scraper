<?php

namespace Xurumelous\TorrentScraper\Adapter;

use Xurumelous\TorrentScraper\AdapterInterface;
use Xurumelous\TorrentScraper\HttpClientAware;
use Xurumelous\TorrentScraper\Entity\SearchResult;
use Symfony\Component\DomCrawler\Crawler;

class KickassTorrentsAdapter implements AdapterInterface
{
    use HttpClientAware;

    public function __construct(array $options = [])
    {

    }

    public function search($query)
    {
        $response = $this->httpClient->get('http://kickass.so/usearch/' . urlencode($query) . '/?field=seeders&sorder=asc&rss=1');
        $crawler = new Crawler((string) $response->getBody());
        $items = $crawler->filterXpath('//channel/item');
        $results = [];

        foreach ($items as $item) {
            $result = new SearchResult();
            $itemCrawler = new Crawler($item);
            $result->setName($itemCrawler->filterXpath('//title')->text());
            $result->setSeeders((int) $itemCrawler->filterXpath('//torrent:seeds')->text());
            $result->setLeechers((int) $itemCrawler->filterXpath('//torrent:peers')->text());
            $result->setTorrentUrl($itemCrawler->filterXpath('//enclosure')->attr('url'));
            $result->setMagnetUrl($itemCrawler->filterXpath('//torrent:magnetURI')->text());

            $results[] = $result;
        }

        return $results;
    }
}
