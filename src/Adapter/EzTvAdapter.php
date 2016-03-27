<?php

namespace Xurumelous\TorrentScraper\Adapter;

use Xurumelous\TorrentScraper\AdapterInterface;
use Xurumelous\TorrentScraper\HttpClientAware;
use Xurumelous\TorrentScraper\Entity\SearchResult;
use Symfony\Component\DomCrawler\Crawler;

class EzTvAdapter implements AdapterInterface
{
    use HttpClientAware;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param $options array
     */
    public function __construct(array $options = ['seeders' => 1, 'leechers' => 1])
    {
        $this->options = $options;
    }

    /**
     * @param string $query
     * @return array
     */
    public function search($query)
    {
        $response = $this->httpClient->get('http://kickass.so/usearch/' . $this->transformSearchString($query) . '/?field=seeders&sorder=asc&rss=1');
        $crawler = new Crawler((string) $response->getBody());
        $items = $crawler->filter('tr.forum_header_border');
        $results = [];

        foreach ($items as $item) {
            $result = new SearchResult();
            $itemCrawler = new Crawler($item);
            $result->setName(trim($itemCrawler->filter('td')->eq(1)->text()));
            $result->setSeeders($this->options['seeders']);
            $result->setLeechers($this->options['leechers']);
            $result->setTorrentUrl($itemCrawler->filter('a.download_1')->eq(0)->attr('href'));
            $result->setMagnetUrl($itemCrawler->filter('a.magnet')->eq(0)->attr('href'));

            $results[] = $result;
        }

        return $results;
    }

    /**
     * Transform every non alphanumeric character into a dash.
     *
     * @param string $searchString
     * @return mixed
     */
    public function transformSearchString($searchString)
    {
        return preg_replace('/[^a-z0-9]/', '-', strtolower($searchString));
    }
}
