Torrent Scraper
===============

[![Build Status](https://travis-ci.org/xurumelous/torrent-scraper.svg?branch=master)](https://travis-ci.org/xurumelous/torrent-scraper)

## About
This library provides an abstraction to search for torrent files accross some torrent websites.

## Usage
```php
<?php

$scraperService = new \Xurumelous\TorrentScraper\TorrentScrapperService(['ezTv', 'kickassTorrents']);
$results = $scraperService->search('elementaryos');

foreach ($results as $result) {
	$result->getName();
    $result->getSeeders();
    $result->getLeechers();
    $result->getTorrentUrl();
    $result->getMagnetUrl();
}
```

## Available adapters
* [ezTv](https://eztv.ag/)
* [kickassTorrents](http://kickass.to) - Needs refactoring since the page changed.
* [thePirateBay](http://thepiratebay.org)
