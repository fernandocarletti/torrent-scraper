<?php

namespace Xurumelous\TorrentScraper;

use Xurumelous\TorrentScraper\Entity\SearchResult;

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
        $expected = [new SearchResult()];
        $adapterMock = $this->getMock('Xurumelous\TorrentScraper\AdapterInterface');
        $adapterMock->expects($this->once())
            ->method('search')
            ->with('The Walking Dead S05E08')
            ->willReturn($expected);

        $service = new TorrentScraperService('null');
        $service->setAdapter($adapterMock);

        $actual = $service->search('The Walking Dead S05E08');

        $this->assertSame($expected, $actual);
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
