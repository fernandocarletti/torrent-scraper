<?php

namespace Xurumelous\TorrentScraper;

class TorrentScraperService
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    public function setAdapter(AdapterInterface $adapter)
    {
        if (!$adapter->getHttpClient())
        {
            $adapter->setHttpClient(new \GuzzleHttp\Client());
        }

        $this->adapter = $adapter;
    }

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function search($query)
    {
        $this->adapter->search($query);
    }
}
