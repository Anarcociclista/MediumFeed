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