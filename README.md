Torrent Scraper
===============

## About
This library provides an abstraction to search for torrent files accross some torrent websites.

## Usage
	```php
	<?php
	
	$scraperService = new \Xurumelous\TorrentScraper\TorrentScrapperService('kickassTorrents');
	$results = $scraperService->search('elemtaryos');
	
	foreach ($results as $result) {
		$result->getName();
	    $result->getSeeders();
	    $result->getLeechers();
	    $result->getTorrentUrl();
	    $result->getMagnetUrl();
	}
	```

## Available adapters
* [kickassTorrents](Kickass.to)

