# new-acquisitions-with-primo

## Requirements
* Calls to exernal APIs are made using [Httpful](http://phphttpclient.com/) "a simple, chainable PHP library intended to make speaking HTTP painless and interacting with REST APIs a breeze"
* jQuery
* your server IP is registered in the appropriate mapping table in Primo backoffice
  
  
  
## Structure
The main idea:
the Javascript file new_acquisitions.js calls the server via AJAX. The call goes to an interface file (new-arrivals/arrival-interface.php), intended for interpreting the request, pass it over to the core classes, receive a response and pass it back to the Javascript file.

The Arrival class (new-arrivals/Arrival.php) used by the interface is responsible for calling the XService provided by Primo ([documented here](https://developers.exlibrisgroup.com/primo/apis/webservices/xservices/search/briefsearch)). It processes the results and then looks for book covers using the Cover class (new-arrivals/Cover.php), which in turn uses the book's ISBN to query OpenLibrary and Google Books. If a covers is found the cover's URL is passed to Arrival. An array of books including bibliographic data, the cover URL and Primo's record permalink is transferred back to the interface.


## TODO
* optimize code to speed the process up
* create customizable parameters in the Javascript file
  * timespan to search for new acquisitions (7, 30 or 90 days)
  * Primo base URL
  * Primo institution
  * Records' sort order (stitle - performs Title sort / scdate - performs a date sort in descending order / scdate2 - performs a date sort in ascending order / screator - performs an author sort / popularity - performs a popularity sort. 
