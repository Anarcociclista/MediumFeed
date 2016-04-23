## What is MediumFeed

A Simple, quick and easy to use composer package for import posts from Medium.com feed

## Installation and usage

Installation:

```bash
composer install anarcociclista/mediumfeed
```


Example usage:

```php
<?php 
use Anarcociclista\feeds\MediumFeed;

require __DIR__ . "/vendor/autoload.php";

$feed = new MediumFeed(
	'https://medium.com/feed/@mauro.peroni',
	[
		"limit" => 5,
		"timezone" => "Europe/Rome"
	]);

foreach($feed as $v) {
	echo $v->title . "<br />";
	echo $v->time . "<br />";
	echo $v->link . "<br />";
	echo $v->imgSrc . "<br />";
	echo $v->abstract . "<br />";
}
```

## License

MIT