<?php

namespace Anarcociclista\feeds;

/**
 * Reads Medium.com feeds, with a quick and minimal configuration
 * @author Enrico Pascucci <enrico.pascucci[at]gmail.com>
 * @license  MIT
 * @version  1.0
 */
class MediumFeed extends \SplQueue {

	/**
	 * @property array
	 */
	protected $config = [];

	/**
	 *  
	 * @param   $url the url of the feed
	 * @param   $config the array containing the configuration parameters: "limit", "timezone", "dateformat", "encoding"
	 */
	public function __construct($url, $config = []){
		
		//load xml from the Feed Class
		$rss = \Feed::loadRss($url);
		$dom = new \DOMDocument();
		$dom->loadHTML($rss->item->description);

		$this->manageConf($config);

		date_default_timezone_set($this->config['timezone']);

		$count = 0;
		foreach($rss->item as $item) {
			$this->push($this->parseDescription($item));

			if (($this->config['limit']) && (++$count == $this->config['limit'])) break;
		}
	}

	/**
	 * @param $item SimpleXMLobj - a feed post
	 */
	protected function parseDescription($item){

		$post = new \stdClass();
		$post->title = $this->encode($item->title, $this->config['charset']);
		
		$date = new \DateTime($item->pubDate);
		$date->setTimezone(new \DateTimezone($this->config['timezone']));
		$post->time = $date->format($this->config['dateformat']);
		
		$post->link = $this->encode($item->link, $this->config['charset']);
		$dom = new \DOMDocument();
		$dom->loadHTML($this->encode($item->description, $this->config['charset']));

		$post->imgSrc = $this->encode(
			$dom->documentElement->getElementsByTagName("img")->item(0)->getAttribute("src"),
			$this->config['charset']
		);
		$post->abstract = $this->encode(
			$dom->documentElement->getElementsByTagName("p")->item(1)->textContent,
			$this->config['charset']
		);
		
		return $post;
	}

	/**
	 * a simple array merge, overwriting the default configuration
	 * 
	 * @param  $params array
	 */
	protected function manageConf($params = []) {
		$default = [
			"limit" => NULL,
			"timezone" => "Europe/London", //http://php.net/manual/en/timezones.php
			"dateformat" => "Y-m-d H:i", // http://php.net/manual/it/function.date.php
			"charset" => "" // ovverride the utf-8 encoding
		];
		$this->config = array_merge($default, $params);
	}

	protected function encode($str, $charset = '') {
		if (($charset) && ('utf-8' !== strtolower($charset))) {
			return mb_convert_encoding(trim($str), $charset);
		} else {
			return trim($str);
		}
	}
}