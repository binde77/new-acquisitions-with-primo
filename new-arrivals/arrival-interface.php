<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/files/scripts/new-arrivals/Arrival.php";

$arrival = new Arrival;
echo $arrival->parsePrimoResults();

// $res = $arrival->getPrimoResults();
// $titles = array();
// foreach ($res as $record) :
// 	$titles[] = $record["PrimoNMBib"]["record"]["display"]["title"] . ' (' . $record["PrimoNMBib"]["record"]["search"]["isbn"] . ')';
// endforeach;
?>

