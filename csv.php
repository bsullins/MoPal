<?php

	require_once("functions.php");

	//get query results
	$results = json_decode(queryES( htmlspecialchars_decode($_POST['ESHost'],ENT_QUOTES), htmlspecialchars_decode(urldecode($_POST["ESQuery"]), ENT_QUOTES)), true);
	$csvData = parseHits($results);

	//create csv for download
	download_send_headers( htmlspecialchars_decode($_POST['filename'],ENT_QUOTES) . "-". date("Y-m-d") . ".csv");
	echo array2csv($csvData);
	die();


?>