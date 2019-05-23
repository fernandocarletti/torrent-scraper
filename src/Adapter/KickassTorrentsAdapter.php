<?php

declare(strict_types=1);

namespace Xurumelous\TorrentScraper\Adapter;

use Symfony\Component\DomCrawler\Crawler;
use Xurumelous\TorrentScraper\AdapterInterface;
use Xurumelous\TorrentScraper\Entity\SearchResult;
use Xurumelous\TorrentScraper\HttpClientAware;

class KickassTorrentsAdapter implements AdapterInterface
{
    use HttpClientAware;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
    }

    /**
     * @param string $query
     *
     * @return SearchResult[]
     */
    public function search($query)
    {
        try {
            $response = $this->httpClient->get('http://kickasstorrents.to/usearch/' . urlencode($query) . '/');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return [];
        }

        $crawler = new Crawler((string) $response->getBody());
        $items = $crawler->filter('table.data.frontPageWidget tr');
        $results = [];

        $i = 0;

        foreach ($items as $item) {
            // Ignores advertisement and header
            if ($i < 2) {
                $i++;

                continue;
            }

            $itemCrawler = new Crawler($item);

            $name = $itemCrawler->filter('.cellMainLink')->text();

            if (!stristr($name, $query)) {
                continue;
            }

            $data = json_decode(str_replace("'", '"', $itemCrawler->filter('div[data-sc-params]')->attr('data-sc-params')));

            $result = new SearchResult();
            $result->setName($name);
            $result->setSeeders((int) $itemCrawler->filter('td:nth-child(5)')->text());
            $result->setLeechers((int) $itemCrawler->filter('td:nth-child(6)')->text());
            $result->setMagnetUrl($data->magnet);

            $torrentSize = (preg_match('/([0-9]+\.*[0-9]*)\ ([G|M]B)/', $itemCrawler->filter('td:nth-child(2)')->text(), $matches)) ? $matches[1] : 0;
            $torrentSizeUnit = $matches[2];
            $torrentSizeConvertedToMib = ($torrentSizeUnit === 'GB') ? $torrentSize * 1024 : $torrentSize;
            $result->setSize((float) $torrentSizeConvertedToMib);

            $results[] = $result;
        }

        return $results;
    }
}
