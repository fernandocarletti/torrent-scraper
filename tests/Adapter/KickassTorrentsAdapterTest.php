<?php

namespace Xurumelous\TorrentScraper;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Xurumelous\TorrentScraper\Adapter\KickassTorrentsAdapter;
use Xurumelous\TorrentScraper\Entity\SearchResult;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class KickassTorrentsAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $rawResultCache;

    public function testIsImplementingAdapterInterface()
    {
        $adapter = new KickassTorrentsAdapter();

        $this->assertInstanceOf('\Xurumelous\TorrentScraper\AdapterInterface', $adapter);
    }

    public function testIsGettingAndSettingHttpClient()
    {
        $adapter = new KickassTorrentsAdapter();

        $adapter->setHttpClient(new Client());
        $actual = $adapter->getHttpClient();

        $this->assertInstanceOf('\GuzzleHttp\Client', $actual);
    }

    public function testIsPerformingSearch()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], $this->getMockRawResult()),
        ]);
        $adapter = new KickassTorrentsAdapter();

        $adapter->setHttpClient(new Client(['handler' => $mockHandler]));
        $result1 = new SearchResult();
        $result1->setName('Ubuntu Linux Toolbox 1000+ Commands for Ubuntu and Debian Power Users by Christopher Negus');
        $result1->setSeeders(30);
        $result1->setLeechers(2);
        $result1->setMagnetUrl('magnet:?xt=urn:btih:D2AA08AB08325A1D9B62EA9EB7F4585148101A00&dn=ubuntu+linux+toolbox+1000+commands+for+ubuntu+and+debian+power+users+by+christopher+negus&tr=udp%3A%2F%2Ftracker.publicbt.com%2Fannounce&tr=udp%3A%2F%2Fglotorrents.pw%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce');

        $result2 = new SearchResult();
        $result2->setName('Debian 7- System Administration Best Practices, 2013 [PDF]~StormRG~');
        $result2->setSeeders(12);
        $result2->setLeechers(1);
        $result2->setMagnetUrl('magnet:?xt=urn:btih:17A41CF831D788317A6F6E776943BD7711E6866D&dn=debian+7+system+administration+best+practices+2013+pdf+stormrg&tr=udp%3A%2F%2Ftracker.publicbt.com%2Fannounce&tr=udp%3A%2F%2Fglotorrents.pw%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce');

        $expected = [$result1, $result2];

        $actual = $adapter->search('Debian');

        $this->assertEquals($expected, $actual);
    }

    public function testIsHandlingExceptionOnNotFound()
    {
        $uri = 'http://kickasstorrents.to/usearch/' . urlencode('The Walking Dead S05E08') . '/';

        $client = $this->getMock(Client::class);
        $client->expects($this->once())
            ->method('__call')
            ->with('get', [$uri])
            ->willThrowException(new ClientException('404 Not Found', new Request('GET', $uri)));

        $adapter = new KickassTorrentsAdapter();
        $adapter->setHttpClient($client);

        $actual = $adapter->search('The Walking Dead S05E08');

        $this->assertEquals([], $actual);
    }

    protected function getMockRawResult()
    {
        if (!$this->rawResultCache) {
            $this->rawResultCache = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'kickass_torrents_result.html');
        }

        return $this->rawResultCache;
    }

    public function testFunctional()
    {
        $adapter = new KickassTorrentsAdapter();
        $adapter->setHttpClient(new Client());

        $actual = $adapter->search('Debian');

        $this->assertTrue(count($actual) > 0);
        $this->assertNotEmpty($actual[0]->getName());
        $this->assertNotNull($actual[0]->getSeeders());
        $this->assertNotNull($actual[0]->getLeechers());
        $this->assertRegExp('/^magnet:.*$/', $actual[0]->getMagnetUrl());
    }
}
