<?php

declare(strict_types=1);

namespace Xurumelous\TorrentScraper\Adapter;

use GuzzleHttp\Exception\ClientException;
use Symfony\Component\DomCrawler\Crawler;
use Xurumelous\TorrentScraper\AdapterInterface;
use Xurumelous\TorrentScraper\Entity\SearchResult;
use Xurumelous\TorrentScraper\HttpClientAware;

class ThePirateBayAdapter implements AdapterInterface
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
            $response = $this->httpClient->get('https://thepiratebay.org/search/' . urlencode($query) . '/0/7/0');
        } catch (ClientException $e) {
            return [];
        }

        $crawler = new Crawler((string) $response->getBody());
        $items = $crawler->filter('#searchResult tr');
        $results = [];
        $first = true;

        foreach ($items as $item) {
            // Ignore the first row, the header
            if ($first) {
                $first = false;
                continue;
            }

            $result = new SearchResult();
            $itemCrawler = new Crawler($item);
            $result->setName(trim($itemCrawler->filter('.detName')->text()));
            $result->setSeeders((int) $itemCrawler->filter('td')->eq(2)->text());
            $result->setLeechers((int) $itemCrawler->filter('td')->eq(3)->text());
            $result->setMagnetUrl($itemCrawler->filterXpath('//tr/td/a')->attr('href'));
            $uploader = null;
            try {
                $uploader = $itemCrawler->filter('.detDesc a')->text();
            } catch (\InvalidArgumentException $e) {
                // Handle the current node list is empty..
            }
            $result->setUploader($uploader);

            $description = $itemCrawler->filter('.detDesc')->text();
            $torrentSize = (preg_match('/.*[Size|Tamanho\ de]\ ([0-9]+\.*[0-9]*).*([G|M]iB)/', $description, $matches)) ? $matches[1] : 0;
            $torrentSizeUnit = (isset($matches[2])) ? $matches[2] : 'MiB';
            $torrentSizeConvertedToMib = ($torrentSizeUnit === 'GiB') ? $torrentSize * 1024 : $torrentSize;
            $result->setSize((float) $torrentSizeConvertedToMib);

            $results[] = $result;
        }

        return $results;
    }
}
