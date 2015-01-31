<?php

namespace Xurumelous\TorrentScraper;

class TorrentScraperServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testIsSettingAndGettingTheAdapter()
    {
        $service = new TorrentScraperService('null');
        $actual = $service->getAdapter();

        $this->assertInstanceOf('\Xurumelous\TorrentScraper\Adapter\NullAdapter', $actual);
    }

    public function testIsSearchingInTheAdapter()
    {
        $adapterMock = $this->getMock('Xurumelous\TorrentScraper\AdapterInterface');
        $adapterMock->expects($this->once())
            ->method('search');

        $service = new TorrentScraperService('null');
        $service->setAdapter($adapterMock);

        $service->search('The Walking Dead S05E08');
    }

    public function testIsHttpClientBeingSet()
    {
        $adapterMock = $this->getMock('Xurumelous\TorrentScraper\AdapterInterface');
        $adapterMock->expects($this->once())
            ->method('setHttpClient')
            ->with(new \GuzzleHttp\Client());

        $service = new TorrentScraperService('null');

        $service->setAdapter($adapterMock);
    }
}
