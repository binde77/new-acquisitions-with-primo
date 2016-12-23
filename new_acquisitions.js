// Customize request
var customParams = {
	desiredAmount: 4, /* (int) max amount of final records you want to receive */
	timespan: '30', /* (string) looks for records inserted in Primo during the last XX days. accepted values are 7, 30, or 90 */
	primoBaseUrl: 'http://usi-primo-test.hosted.exlibrisgroup.com',
	primoInstitution: 'ARC',
	queryTerm: 'arc',
	sortField: 'scdate',
	bulkSize: '100' /* (string) amount of records to retrieve from primo to check against bookcovers existence */
};

// call the php interface to retrieve 'desiredAmount' of new acquisitions
$.post("/files/scripts/new-arrivals/arrival-interface.php", customParams,
    function(data){
    	// do whatever you want with 'data' ...

    	// you can parse and prettify
		var obj = JSON.parse(data);
		var pretty = JSON.stringify(obj, null, 2);
		console.log(pretty)
		/* Sample output:
		[
		  {
		    "lds14": "ed. by Teresa Stoppani ... [et al.]",
		    "title": "This thing called theory",
		    "publisher": "London : Routledge, 2017",
		    "format": "316 p.",
		    "language": {
		      "it": "inglese",
		      "en": "English"
		    },
		    "isbn": "978-1-138-22299-1",
		    "coverUrl": "http://books.google.com/books/content?id=IXOVDAEACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api",
		    "coverEngine": "Google Books",
		    "permalink": "http://lumen.sbu.usi.ch/arc:Collezioni della biblioteca:arc_aleph000842721"
		  },
		  {
		    "lds14": "Mihye An",
		    "title": "Atlas of fantastic infrastructures : an intimate look at media architecture",
		    "publisher": "Basel : Birkhäuser, 2016",
		    "format": "392 p. : ill.",
		    "language": {
		      "it": "inglese",
		      "en": "English"
		    },
		    "isbn": "978-3-0356-0628-7",
		    "coverUrl": "http://books.google.com/books/content?id=qCpKrgEACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api",
		    "coverEngine": "Google Books",
		    "permalink": "http://lumen.sbu.usi.ch/arc:Collezioni della biblioteca:arc_aleph000842678"
		  },
		  {
		    "lds14": "Kate Hill",
		    "title": "Culture and class in English public museums, 1850-1914",
		    "publisher": "London : Routledge, 2016",
		    "format": "174 p. : ill.",
		    "language": {
		      "it": "inglese",
		      "en": "English"
		    },
		    "isbn": "978-0-7546-0432-7",
		    "coverUrl": "http://covers.openlibrary.org/b/ISBN/9780754604327-L.jpg?default=false",
		    "coverEngine": "Open Library",
		    "permalink": "http://lumen.sbu.usi.ch/arc:Collezioni della biblioteca:arc_aleph000841182"
		  },
		  {
		    "lds14": "[Hrsg.: Heinz Wirz]",
		    "title": "Dreier Frenzel",
		    "publisher": "Luzern : Quart Verlag, 2016",
		    "format": "45 p. : ill.",
		    "language": {
		      "it": "tedesco",
		      "en": "German"
		    },
		    "isbn": "978-3-03761-124-1",
		    "coverUrl": "http://books.google.com/books/content?id=AcgHjwEACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api",
		    "coverEngine": "Google Books",
		    "permalink": "http://lumen.sbu.usi.ch/arc:Collezioni della biblioteca:arc_aleph000842255"
		  }
		]
		*/

}).fail(function(){
      console.log("error");
});