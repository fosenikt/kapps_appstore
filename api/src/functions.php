<?php

/**
	Output function for handler
*/
function output($result, $redir='')
{
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
	//exit();
}


function rpc_call_curl_post($url, $arr) {
	//url-ify the data for the POST
	$field_string = http_build_query($arr);

	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 20.11.17, RA: Added to work with HTTPS

	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, 1);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $field_string);

	$data = curl_exec($ch);
	curl_close($ch);


	//error_log(join(', ', $arr), 0);
	//error_log("$data", 0);

	$data = json_decode($data);
	$data = objectToArray($data);

	return $data;
}

function objectToArray($d) {
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}
	
	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	}
	else {
		// Return array
		return $d;
	}
}



/**
* Minutes to string-time (hour:min)
*
* @param  	Int			$min			Minutes
* @return  	String		$tid			String of time (hour:min)
*/

function date_min2hourmin($min) {
    
    if ($min == 0) return '';

    $tid = ($min / 60);
    list($timer, $minutter) = explode(".", $tid);
    
    $minutter = round("0.$minutter" * 60);
    
    $tid = "$timer:$minutter";
    
    return $tid;
}