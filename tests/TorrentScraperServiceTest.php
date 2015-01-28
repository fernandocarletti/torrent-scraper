<?php

namespace Xurumelous\TorrentScraper;

class TorrentScraperServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testIsSettingAndGettingTheAdapter()
    {
        $adapterMock = $this->getMock('Xurumelous\TorrentScraper\AdapterInterface');
        $service = new TorrentScraperService();

        $service->setAdapter($adapterMock);
        $actual = $service->getAdapter();

        $this->assertSame($adapterMock, $actual);
    }

    public function testIsSearchingInTheAdapter()
    {
        $adapterMock = $this->getMock('Xurumelous\TorrentScraper\AdapterInterface');
        $adapterMock->expects($this->once())
            ->method('search');

        $service = new TorrentScraperService();
        $service->setAdapter($adapterMock);

        $service->search('The Walking Dead S05E08');
    }

    public function testIsHttpClientBeingSet()
    {
        $adapterMock = $this->getMock('Xurumelous\TorrentScraper\AdapterInterface');
        $adapterMock->expects($this->once())
            ->method('setHttpClient')
            ->with(new \GuzzleHttp\Client());

        $service = new TorrentScraperService();

        $service->setAdapter($adapterMock);
    }
}
