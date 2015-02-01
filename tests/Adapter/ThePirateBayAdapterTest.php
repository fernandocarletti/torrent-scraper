<?php

namespace Xurumelous\TorrentScraper;

use Xurumelous\TorrentScraper\Adapter\ThePirateBayAdapter;
use Xurumelous\TorrentScraper\Entity\SearchResult;
use GuzzleHttp\Client;
use GuzzleHttp\Ring\Client\MockHandler;

class ThePirateBayAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $rawResultCache;

    public function testIsImplementingAdapterInterface()
    {
        $adapter = new ThePirateBayAdapter();

        $this->assertInstanceOf('\Xurumelous\TorrentScraper\AdapterInterface', $adapter);
    }

    public function testIsGettingAndSettingHttpClient()
    {
        $adapter = new ThePirateBayAdapter();

        $adapter->setHttpClient(new Client());
        $actual = $adapter->getHttpClient();

        $this->assertInstanceOf('\GuzzleHttp\Client', $actual);
    }

    public function testIsPerformingSearch()
    {
        $mockHandler = new MockHandler([
            'status' => 200,
            'body' => $this->getMockRawResult(),
        ]);
        $adapter = new ThePirateBayAdapter();

        $adapter->setHttpClient(new Client(['handler' => $mockHandler]));
        $result1 = new SearchResult();
        $result1->setName('elementaryos-beta2-i386.20130506.iso');
        $result1->setSeeders(1);
        $result1->setLeechers(0);
        $result1->setTorrentUrl(null);
        $result1->setMagnetUrl('magnet:?xt=urn:btih:&dn=elementaryos-beta2-i386.20130506.iso&tr=udp%3A%2F%2Fopen.demonii.com%3A1337&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');

        $result2 = new SearchResult();
        $result2->setName('elementaryos-beta2-amd64.20130506.iso');
        $result2->setSeeders(1);
        $result2->setLeechers(0);
        $result2->setTorrentUrl(null);
        $result2->setMagnetUrl('magnet:?xt=urn:btih:&dn=elementaryos-beta2-amd64.20130506.iso&tr=udp%3A%2F%2Fopen.demonii.com%3A1337&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');

        $result3 = new SearchResult();
        $result3->setName('ElementaryOS 64-bit 20130810');
        $result3->setSeeders(1);
        $result3->setLeechers(0);
        $result3->setTorrentUrl(null);
        $result3->setMagnetUrl('magnet:?xt=urn:btih:&dn=ElementaryOS+64-bit+20130810&tr=udp%3A%2F%2Fopen.demonii.com%3A1337&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');

        $expected = [$result1, $result2, $result3];

        $actual = $adapter->search('The Walking Dead S05E08');

        $this->assertEquals($expected, $actual);
    }

    protected function getMockRawResult()
    {
        if (!$this->rawResultCache) {
            $this->rawResultCache = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'the_pirate_bay_result.html');
        }

        return $this->rawResultCache;
    }
}
