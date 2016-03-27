<?php

namespace Xurumelous\TorrentScraper;

use Xurumelous\TorrentScraper\Adapter\EzTvAdapter;
use Xurumelous\TorrentScraper\Entity\SearchResult;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class EzTvBayAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $rawResultCache;

    public function testIsImplementingAdapterInterface()
    {
        $adapter = new EzTvAdapter();

        $this->assertInstanceOf('\Xurumelous\TorrentScraper\AdapterInterface', $adapter);
    }

    public function testIsGettingAndSettingHttpClient()
    {
        $adapter = new EzTvAdapter();

        $adapter->setHttpClient(new Client());
        $actual = $adapter->getHttpClient();

        $this->assertInstanceOf('\GuzzleHttp\Client', $actual);
    }

    public function testIsTransformingSearchString()
    {
        $expected = 'marvel-s-agents-of-s-h-i-e-l-d-';

        $adapter = new EzTvAdapter();
        
        $actual = $adapter->transformSearchString('Marvel\'s Agents of S.H.I.E.L.D.');
        
        $this->assertEquals($expected, $actual);
    }

    public function testIsPerformingSearch()
    {
        $mockHandler = new MockHandler([
            new Response(200, [], $this->getMockRawResult()),
        ]);
        $adapter = new EzTvAdapter(['seeders' => 15, 'leechers' => 20]);

        $adapter->setHttpClient(new Client(['handler' => $mockHandler]));
        $result1 = new SearchResult();
        $result1->setName('Marvels Agents of S H I E L D S03E13 HDTV x264-FLEET');
        $result1->setSeeders(15);
        $result1->setLeechers(20);
        $result1->setTorrentUrl('https://zoink.ch/torrent/Marvels.Agents.of.S.H.I.E.L.D.S03E13.HDTV.x264-FLEET[eztv].mp4.torrent');
        $result1->setMagnetUrl('magnet:?xt=urn:btih:22db54ed3b1e55a2f998c531b8d504984adcce90&dn=Marvels.Agents.of.S.H.I.E.L.D.S03E13.HDTV.x264-FLEET%5Beztv%5D.mp4%5Beztv%5D&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A80&tr=udp%3A%2F%2Fglotorrents.pw%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');

        $result2 = new SearchResult();
        $result2->setName('Marvels Agents of S H I E L D S03E13 720p HDTV x264-AVS');
        $result2->setSeeders(15);
        $result2->setLeechers(20);
        $result2->setTorrentUrl('https://zoink.ch/torrent/Marvels.Agents.of.S.H.I.E.L.D.S03E13.720p.HDTV.x264-AVS[eztv].mkv.torrent');
        $result2->setMagnetUrl('magnet:?xt=urn:btih:adc79cf1d0c3d855f369ca4d43894927e34b6d7a&dn=Marvels.Agents.of.S.H.I.E.L.D.S03E13.720p.HDTV.x264-AVS%5Beztv%5D.mkv%5Beztv%5D&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A80&tr=udp%3A%2F%2Fglotorrents.pw%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');

        $result3 = new SearchResult();
        $result3->setName('Marvels Agents of S H I E L D S03E12 HDTV x264-KILLERS');
        $result3->setSeeders(15);
        $result3->setLeechers(20);
        $result3->setTorrentUrl('https://zoink.ch/torrent/Marvels.Agents.of.S.H.I.E.L.D.S03E12.HDTV.x264-KILLERS[eztv].mp4.torrent');
        $result3->setMagnetUrl('magnet:?xt=urn:btih:dac1f9ba51c08d32d113f35f0c17c5fe09381c38&dn=Marvels.Agents.of.S.H.I.E.L.D.S03E12.HDTV.x264-KILLERS%5Beztv%5D.mp4%5Beztv%5D&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A80&tr=udp%3A%2F%2Fglotorrents.pw%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce&tr=udp%3A%2F%2Fexodus.desync.com%3A6969');

        $expected = [$result1, $result2, $result3];

        $actual = $adapter->search('Marvel\'s Agents of S.H.I.E.L.D.');

        $this->assertEquals($expected, $actual);
    }

    protected function getMockRawResult()
    {
        if (!$this->rawResultCache) {
            $this->rawResultCache = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'eztv_result.html');
        }

        return $this->rawResultCache;
    }
}
