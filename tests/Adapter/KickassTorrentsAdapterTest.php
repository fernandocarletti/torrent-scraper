<?php

namespace Xurumelous\TorrentScraper;

use Xurumelous\TorrentScraper\Adapter\KickassTorrentsAdapter;
use Xurumelous\TorrentScraper\Entity\SearchResult;
use GuzzleHttp\Client;
use GuzzleHttp\Ring\Client\MockHandler;

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
            'status' => 200,
            'body' => $this->getMockRawResult(),
        ]);
        $adapter = new KickassTorrentsAdapter();

        $adapter->setHttpClient(new Client(['handler' => $mockHandler]));
        $result1 = new SearchResult();
        $result1->setName('elementaryos beta2 amd64 20130506 iso');
        $result1->setSeeders(48);
        $result1->setLeechers(2);
        $result1->setTorrentUrl('http://torcache.net/torrent/AC86FCA020C96066862DA1B5FCDF967E2622528D.torrent?title=[kickass.so]elementaryos.beta2.amd64.20130506.iso');
        $result1->setMagnetUrl('magnet:?xt=urn:btih:AC86FCA020C96066862DA1B5FCDF967E2622528D&dn=elementaryos+beta2+amd64+20130506+iso&tr=udp%3A%2F%2Ffr33domtracker.h33t.com%3A3310%2Fannounce&tr=udp%3A%2F%2Fopen.demonii.com%3A1337');

        $result2 = new SearchResult();
        $result2->setName('elementaryos beta1 i386 20121114 iso');
        $result2->setSeeders(47);
        $result2->setLeechers(3);
        $result2->setTorrentUrl('http://torcache.net/torrent/B1373BF8253B5462A1FAA36F6F0288152D590841.torrent?title=[kickass.so]elementaryos.beta1.i386.20121114.iso');
        $result2->setMagnetUrl('magnet:?xt=urn:btih:B1373BF8253B5462A1FAA36F6F0288152D590841&dn=elementaryos+beta1+i386+20121114+iso&tr=udp%3A%2F%2Ffr33domtracker.h33t.com%3A3310%2Fannounce&tr=udp%3A%2F%2Fopen.demonii.com%3A1337');

        $result3 = new SearchResult();
        $result3->setName('elementaryos-stable-amd64.20130810.iso');
        $result3->setSeeders(0);
        $result3->setLeechers(1);
        $result3->setTorrentUrl('http://torcache.net/torrent/43E60180DEF1CA83A84A6B793C67F726681FB731.torrent?title=[kickass.so]elementaryos.stable.amd64.20130810.iso');
        $result3->setMagnetUrl('magnet:?xt=urn:btih:43E60180DEF1CA83A84A6B793C67F726681FB731&dn=elementaryos+stable+amd64+20130810+iso&tr=udp%3A%2F%2Fopen.demonii.com%3A1337%2Fannounce');

        $expected = [$result1, $result2, $result3];

        $actual = $adapter->search('The Walking Dead S05E08');

        $this->assertEquals($expected, $actual);
    }

    protected function getMockRawResult()
    {
        if (!$this->rawResultCache) {
            $this->rawResultCache = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'kickass_torrents_result.html');
        }

        return $this->rawResultCache;
    }
}
