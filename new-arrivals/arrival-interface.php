<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/files/scripts/new-arrivals/Arrival.php";

// POST params
// $_POST["desiredAmount"]
// $_POST["timespan"]
// $_POST["primoBaseUrl"]
// $_POST["primoInstitution"]
// $_POST["sortField"]
// $_POST["queryTerm"]
// $_POST["bulkSize"]

// create Primo XService Url
$primoXservice = $_POST["primoBaseUrl"] . '/PrimoWebServices/xservice/search/brief?institution=' . $_POST["primoInstitution"] . '&query=any,contains,' . $_POST["queryTerm"] .'&query=facet_newrecords,exact,' . $_POST["timespan"] . '+days+back&sortField=' . $_POST["sortField"] . '&onCampus=false&indx=1&bulkSize=' . $_POST["bulkSize"] . '&json=true';

$arrival = new Arrival($primoXservice, $_POST["desiredAmount"]);
echo $arrival->parsePrimoResults();

?>

