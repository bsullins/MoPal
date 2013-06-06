<?php

//query Elastic Search
function queryES($host, $query){
	
	$params = array(''=>$query); // it doesn't take a key for this query
	
	
	try{

		$resp =   http_parse_message(http_post_data($host, $query))->body;
		// echo '<script>alert(\'pass\');</script>';
		return $resp;
		
	} catch(Exception $e) {

		echo '<script>alert(\'fail\');</script>';		
		return false;

	}
}

//Check if $value is null and replace with $r
function ifNull($value, $r="#unknown"){

	if ( is_array($value) ){
		$value="Array";
	} else {

		if( is_null($value) || $value=="" || strlen($value)==0 ) {
			$value=$r;
		}
	}

	return $value;
}

//print an HTML table of a multi-dimensional associative array
function array2table($array, $class, $preview = 10){

	$headers = array();
	$thead = "<thead>";

	foreach($array as $innerArray) {
		foreach($innerArray as $key => $value) {
			if (!in_array($key, $headers)) {
				$thead .= "<th>" . $key . "</th>";
				$headers[] = $key;
			}
		}
	}

	$thead .= "</thead>";
	$tbody = "<tbody>";
	$i = 1;

	foreach($array as $innerArray) {

		if ($i >= $preview) {
			break;
		}

		$tbody .= "<tr>";
		foreach($headers as $th) {
			$tbody .= "<td>";
			if (isset($innerArray[$th])) {

				//if it's an array, parse it into a comma delimited string
				if (is_array($innerArray[$th]) ){
					$tbody .= implode(", ",$innerArray[$th]);
				} else {
					$tbody .= $innerArray[$th];
				}


			}
			$tbody .= "</td>";
		}

		$tbody .= "</tr>";
		$i++;
	}

	$table = "<table class=\"$class\">" . $thead . $tbody . "</table>";

	return $table;
}

//save a multi-dimensional associative array to a csv file
function array2csv($array){

	if (count($array) == 0) {
		return null;
	} else {

	$headers = array();
	$csvData = array();

	//get the headers first since you could be missing values
	foreach($array as $innerArray) {
		foreach($innerArray as $key => $value) {
			if (!in_array($key, $headers)) {
				$headers[] = $key;
			}
		}
	}

	//now that we have all the headers let’s add them to the csvData
	$csvData[] = $headers;

	foreach($array as $innerArray) {
		$csvRow = array();
		foreach($headers as $rowItem) {
			$value = "";
            if (isset($innerArray[$rowItem])) {
                $value = $innerArray[$rowItem];
            }
			$csvRow[] = $value;
        }

		$csvData[] = $csvRow;
	}

//		return $csvData;
		ob_start();
		$df = fopen("php://output", 'w');
//		fputcsv($df, array_keys(reset($csvData)));
		foreach ($csvData as $row) {
			fputcsv($df, $row);
		}
		fclose($df);
		return ob_get_clean();
	}
}

//stream csv instead of saving it locally
function download_send_headers($filename) {
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header("Content-Disposition: attachment;filename={$filename}");
	header("Content-Transfer-Encoding: binary");
}

//parse ES 'hits'
function parseHits($results){

	if( !is_array($results['hits']['hits']) ){
		?><script>alert("Oops! The array you passed doesn't appear to have any hits");</script><?php

		return null;

	} else {

		//setup control vars
		$hits = $results['hits']['hits'];
		$count = count($hits)-1;

		//load data into single array
		$data = array();

		for($i=0; $i<=$count; $i++) {
			foreach($hits[$i]['fields'] as $key=>$val){
				$data[$i][$key] = strip_tags(ifNull($val));
			}
		}

		return $data;
	}
}

//get relative URL
function formUrl($page)
{
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
	$protocol = substr($sp, 0, strpos($sp, "/")) . $s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	$ref = explode("/",$_SERVER['REQUEST_URI']);
	array_pop($ref);
	$path = implode("/",$ref);

	return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $path . "/" . $page; //;
}

?>