<?php

namespace Xurumelous\TorrentScraper;

use Xurumelous\TorrentScraper\Entity\SearchResult;

class TorrentScraperService
{
    /**
     * @var AdapterInterface[]
     */
    protected $adapters;

    /**
     * @param array $adapters
     * @param array $options
     */
    public function __construct(array $adapters, $options = [])
    {
        foreach ($adapters as $adapter) {
            $adapterName = __NAMESPACE__ . '\\Adapter\\' . ucfirst($adapter) . 'Adapter';
            $this->addAdapter(new $adapterName($options));
        }
    }

    /**
     * @param AdapterInterface $adapter
     */
    public function addAdapter(AdapterInterface $adapter)
    {
        if (!$adapter->getHttpClient())
        {
            $adapter->setHttpClient(new \GuzzleHttp\Client());
        }

        $this->adapters[] = $adapter;
    }

    /**
     * @return AdapterInterface[]
     */
    public function getAdapters()
    {
        return $this->adapters;
    }

    /**
     * @param string $query
     * @return SearchResult[]
     */
    public function search($query)
    {
        $results = [];

        foreach ($this->adapters as $adapter) {
            $results = array_merge($adapter->search($query), $results);
        }

        return $results;
    }
}
