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

// get interface language
function isEng() {
  return ($("body").hasClass("EXLCurrentLang_en_US")) ? true : false;
}

// wait for DOM
$(function() {
	// hide the static new books
	$(".newacquisition").hide();
});

// call the php interface to retrieve 4 new acquisitions
$.post("/files/scripts/new-arrivals/arrival-interface.php", customParams,
    function(data){
		$(".loader").hide();
		var obj = JSON.parse(data);
		var pretty = JSON.stringify(obj, null, 2);

		var divs = $(".newacquisition");
		for (var i = 0; i < obj.length; i++) {
			$(divs[i]).find("img").attr("src", obj[i].coverUrl);
			$(divs[i]).find(".newacquisition-title").text(obj[i].title);
			if (obj[i].lds14) {
				$(divs[i]).find(".newacquisition-responsibility").text(obj[i].lds14);
			} else {
				$(divs[i]).find(".newacquisition-responsibility").text("");
			}
			$(divs[i]).find(".newacquisition-publisher").text(obj[i].publisher);
			$(divs[i]).find(".newacquisition-format").text(obj[i].format);
			if (!isEng()) {
				$(divs[i]).find(".newacquisition-language").text(obj[i].language.it);
			} else {
				$(divs[i]).find(".newacquisition-language").text(obj[i].language.en);
			}
			$(divs[i]).find(".newacquisition-isbn").text(obj[i].isbn);
			$(divs[i]).find(".newacquisition-linktoprimo").attr("href", obj[i].permalink);
		}
		// show the updated new books
		$(".newacquisition").show();
}).fail(function(){
      console.log("error");
});