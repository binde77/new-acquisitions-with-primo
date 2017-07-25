<?php

include_once Cover.php';

/**
*
*/
class Arrival
{

	private static $primoQuery;
	private static $desiredAmount;

	function __construct($query, $amount)
	{
		self::$primoQuery = $query;
		self::$desiredAmount = $amount;
	}

	private static $languageIta = array(
		"ita" => "italiano",
		"ger" => "tedesco",
		"eng" => "inglese",
		"fre" => "francese",
		"rus" => "russo"
	);

	private static $languageEng = array(
		"ita" => "Italian",
		"ger" => "German",
		"eng" => "English",
		"fre" => "French",
		"rus" => "Russian"
	);

	public function manageLanguage($languagesFromPnx)
	{
		// languagesFromPnx is either string (ex: "fre"), or array (ex: array(0 => "eng", 1 => "chi")
		$languages = array();
		$itaLangs = array();
		$engLangs = array();

		if (is_array($languagesFromPnx)) {
			foreach ($languagesFromPnx as $key => $pnxLang):
				$itaLangs[] = self::$languageIta[$pnxLang];
				$engLangs[] = self::$languageEng[$pnxLang];
			endforeach;
		} else {
			$itaLangs[] = self::$languageIta[$languagesFromPnx];
			$engLangs[] = self::$languageEng[$languagesFromPnx];
		}

		$languages["it"] = implode(", ", $itaLangs);
		$languages["en"] = implode(", ", $engLangs);

		return $languages;
	}

	public function getPrimoResults()
	{
		$json = json_decode(file_get_contents(self::$primoQuery), TRUE);
		json_decode(json_encode($json), true);
		return $json["SEGMENTS"]["JAGROOT"]["RESULT"]["DOCSET"]["DOC"];
	}

	public function parsePrimoResults()
	{
		// this is the array that will hold the cover-provided books we will find
		$booksWithCover = array();

		// loop through the results to find books with a cover. if found, retrieve their bibliographic data too.
		$newBooksFromPrimo = $this->getPrimoResults();
		foreach ($newBooksFromPrimo as $primoValues):

			// if we already have enough books, exit the loop
			if (count($booksWithCover) == self::$desiredAmount) {
				break;
			}

			$pnx = $primoValues["PrimoNMBib"]["record"];
			// look for arrays of useful metadata

				// extract book's ISBN (if present), otherwise jump to next loop element
				if ( isset($pnx["search"]["isbn"]) && !is_array($pnx["search"]["isbn"]) ) {
					$removeChars = array('-',' ');
					$originalIsbn = $pnx["search"]["isbn"];
					$isbn = str_replace($removeChars, '', $originalIsbn);
				} else {
					continue;
				}

				// we have the isbn, now we crawl for its cover
				$cover = new Cover($isbn);
				$coverUrl = $cover->getCoverUrl();

				// if we have a cover, create an array with complete data about the book, otherwise jump to next loop element
				if ($coverUrl) {
					// set the book's bibliographic and cover values
					$bookWithCover = array(
						"lds14" => $pnx["display"]["lds14"],
						"title" => $pnx["display"]["title"],
						"publisher" => $pnx["display"]["publisher"],
						"format" => $pnx["display"]["format"],
						"language" => $this->manageLanguage($pnx["facets"]["language"]),
						"isbn" => $originalIsbn,
						"coverUrl" => $coverUrl['cover'],
						"coverEngine" => $coverUrl['engineName'],
						"permalink" => "http://lumen.sbu.usi.ch/arc:Collezioni della biblioteca:arc_aleph" . $pnx["control"]["sourcerecordid"]
					);

					// finally, add the book to the array of books with cover
					$booksWithCover[] = $bookWithCover;

				}
				else {
					continue;
				}
		endforeach;

		// now let's return the array
		return json_encode($booksWithCover);
	}

}

?>
