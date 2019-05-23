<?php

declare(strict_types=1);

namespace Xurumelous\TorrentScraper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Xurumelous\TorrentScraper\Adapter\ThePirateBayAdapter;
use Xurumelous\TorrentScraper\Entity\SearchResult;

class ThePirateBayAdapterTest extends TestCase
{
    protected $rawResultCache;

    public function testIsImplementingAdapterInterface(): void
    {
        $adapter = new ThePirateBayAdapter();

        $this->assertInstanceOf('\Xurumelous\TorrentScraper\AdapterInterface', $adapter);
    }

    public function testIsGettingAndSettingHttpClient(): void
    {
        $adapter = new ThePirateBayAdapter();

        $adapter->setHttpClient(new Client());
        $actual = $adapter->getHttpClient();

        $this->assertInstanceOf('\GuzzleHttp\Client', $actual);
    }

    public function testIsPerformingSearch(): void
    {
        $mockHandler = new MockHandler([
            new Response(200, [], $this->getMockRawResult()),
        ]);
        $adapter = new ThePirateBayAdapter();

        $adapter->setHttpClient(new Client(['handler' => $mockHandler]));
        $result1 = new SearchResult();
        $result1->setName('elementaryos-beta2-i386.20130506.iso');
        $result1->setUploader('ixcoder');
        $result1->setSeeders(1);
        $result1->setLeechers(0);
        $result1->setTorrentUrl(null);
        $result1->setMagnetUrl('magnet:?xt=urn:btih:&dn=elementaryos-beta2-i386.20130506.iso&tr=udp%3A%2F%2Fopen.demonii.com%3A1337&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');
        $result1->setSize(648.00);

        $result2 = new SearchResult();
        $result2->setName('elementaryos-beta2-amd64.20130506.iso');
        $result2->setUploader('ixcoder');
        $result2->setSeeders(1);
        $result2->setLeechers(0);
        $result2->setTorrentUrl(null);
        $result2->setMagnetUrl('magnet:?xt=urn:btih:&dn=elementaryos-beta2-amd64.20130506.iso&tr=udp%3A%2F%2Fopen.demonii.com%3A1337&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');
        $result2->setSize(680.00);

        $result3 = new SearchResult();
        $result3->setName('ElementaryOS 64-bit 20130810');
        $result3->setUploader(null);
        $result3->setSeeders(1);
        $result3->setLeechers(0);
        $result3->setTorrentUrl(null);
        $result3->setMagnetUrl('magnet:?xt=urn:btih:&dn=ElementaryOS+64-bit+20130810&tr=udp%3A%2F%2Fopen.demonii.com%3A1337&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');
        $result3->setSize(694.00);

        $expected = [$result1, $result2, $result3];

        $actual = $adapter->search('The Walking Dead S05E08');

        $this->assertEquals($expected, $actual);
    }

    public function testIsHandlingExceptionOnNotFound(): void
    {
        $uri = 'https://thepiratebay.org/search/' . urlencode('The Walking Dead S05E08') . '/0/7/0';

        $client = $this->getMockBuilder(Client::class)->getMock();
        $client->expects($this->once())
            ->method('__call')
            ->with('get', [$uri])
            ->willThrowException(new ClientException('404 Not Found', new Request('GET', $uri)));

        $adapter = new ThePirateBayAdapter();
        $adapter->setHttpClient($client);

        $actual = $adapter->search('The Walking Dead S05E08');

        $this->assertEquals([], $actual);
    }

    public function testFunctional(): void
    {
        $adapter = new ThePirateBayAdapter();
        $adapter->setHttpClient(new Client());

        $actual = $adapter->search('Debian');

        $this->assertTrue(count($actual) > 0);
        $this->assertNotEmpty($actual[0]->getName());
        $this->assertNotNull($actual[0]->getSeeders());
        $this->assertNotNull($actual[0]->getLeechers());
        $this->assertNull($actual[0]->getTorrentUrl());
        $this->assertRegExp('/^magnet:.*$/', $actual[0]->getMagnetUrl());
        $this->assertNotNull($actual[0]->getSize());
    }

    protected function getMockRawResult()
    {
        if (!$this->rawResultCache) {
            $this->rawResultCache = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'the_pirate_bay_result.html');
        }

        return $this->rawResultCache;
    }
}
