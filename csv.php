<?php

	require_once("functions.php");

	//get query results
	$results = json_decode(queryES($_POST['ESHost'], urldecode($_POST["ESQuery"])), true);
	$csvData = parseHits($results);

	//create csv for download
	download_send_headers($_POST['filename'] . "-". date("Y-m-d") . ".csv");
	echo array2csv($csvData);
	die();


?>