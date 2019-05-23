<?php

declare(strict_types=1);

namespace Xurumelous\TorrentScraper;

use PHPUnit\Framework\TestCase;
use Xurumelous\TorrentScraper\Entity\SearchResult;

class TorrentScraperServiceTest extends TestCase
{
    public function testIsSettingAndGettingTheAdapter(): void
    {
        $service = new TorrentScraperService(['null']);
        $actual = $service->getAdapters();

        $this->assertInstanceOf('\Xurumelous\TorrentScraper\Adapter\NullAdapter', $actual[0]);
    }

    public function testIsSearchingInTheAdapters(): void
    {
        $expected = [new SearchResult()];
        $adapter1 = $this->getMockBuilder(AdapterInterface::class)->getMock();
        $adapter1->expects($this->once())
            ->method('search')
            ->with('The Walking Dead S05E08')
            ->willReturn($expected);

        $adapter2 = $this->getMockBuilder(AdapterInterface::class)->getMock();
        $adapter2->expects($this->once())
            ->method('search')
            ->with('The Walking Dead S05E08')
            ->willReturn([]);

        $service = new TorrentScraperService([]);
        $service->addAdapter($adapter1);
        $service->addAdapter($adapter2);

        $actual = $service->search('The Walking Dead S05E08');

        $this->assertSame($expected, $actual);
    }

    public function testIsHttpClientBeingSet(): void
    {
        $adapterMock = $this->getMockBuilder(AdapterInterface::class)->getMock();
        $adapterMock->expects($this->once())
            ->method('setHttpClient')
            ->with(new \GuzzleHttp\Client());

        $service = new TorrentScraperService([]);

        $service->addAdapter($adapterMock);
    }
}
