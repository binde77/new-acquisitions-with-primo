<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/files/perio/httpful.phar';

/**
*
*/
class Cover
{

	private static $isbn = null;

	function __construct($input)
	{
		self::$isbn = $input;
	}

	public function cUrl($url)
	{
		$handle = curl_init($url);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

		/* Get the HTML or whatever is linked in $url. */
		$response = curl_exec($handle);

		/* Check for 404 (file not found). */
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		if($httpCode == 404) {
		    return false;
		}

		curl_close($handle);

		/* Handle $response here. */
		return $response;
	}

	public function askOpenLibrary($size)
	{
		$url = 'http://covers.openlibrary.org/b/ISBN/' . self::$isbn . '-' . $size . '.jpg?default=false';
		if (gettype($this->cUrl($url)) != 'boolean') {
			return $url;
		} else {
			return false;
		}
	}

	public function askGoogleBooks()
	{
		// APIkey : AIzaSyBLjzV81R6JhSi4DP9BnTASuERUbma7XuY
		$uri = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' . self::$isbn;
		$response = \Httpful\Request::get($uri)->send();
		return $response;
		// $response = $this->cUrl($uri);
		// var_dump($response);
	}

	public function getCoverUrl()
	{
		// array of cover engines. truthCondition is bool; if false, cover is unavailable
		$googleBooksResponse = $this->askGoogleBooks()->body;
		if ( isset($googleBooksResponse->items[0]->volumeInfo->imageLinks) ) {
			$gbTruth = $googleBooksResponse->totalItems;
			$gbcover = ($gbTruth) ? $googleBooksResponse->items[0]->volumeInfo->imageLinks->thumbnail : false;
		} else {
			$gbTruth = false;
			$gbcover = false;
		}

		$coverEngines = array(
				'Open Library' => array(
					'truthCondition' => $this->askOpenLibrary('L'),
					'cover' => $this->askOpenLibrary('L')
					// to disable OpenLibrary uncomment the following two lines
					// 'truthCondition' => false,
					// 'cover' => false
				),
				'Google Books' => array(
					'truthCondition' => $gbTruth,
					'cover' => $gbcover
				)
		);
		// loop trough engines. if truthCondition id true, return the cover url
		foreach ($coverEngines as $name => $engine):
			if ($engine['truthCondition']) {
				return array('cover' => $engine['cover'], 'engineName' => $name);
			}
		endforeach;

		// if code gets here, no cover was found
		return false;
	}

}

?>