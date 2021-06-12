<?php

// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value

function CallAPI($method, $url, $data = false)
{
	global $api_base_url;
	global $api_token;

	error_log('Calling API: ' . $api_base_url.$url);


	$header = array();
	$header[] = 'Content-length: 0';
	$header[] = 'Content-type: application/json';
	$header[] = 'Authorization: Bearer ' . $api_token;

	error_log(json_encode($header));


	$curl = curl_init();

	switch ($method)
	{
		case "POST":
			curl_setopt($curl, CURLOPT_POST, 1);

			if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
		case "PUT":
			curl_setopt($curl, CURLOPT_PUT, 1);
			break;
		default:
			if ($data)
				$url = sprintf("%s?%s", $url, http_build_query($data));
	}

	// Optional Authentication:
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

	curl_setopt($curl, CURLOPT_URL, $api_base_url.$url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	$result = curl_exec($curl);

	$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if (curl_errno($curl)) {
		error_log(curl_error($curl));
	}

	curl_close($curl);

	if (isset($error_msg)) {
		error_log('Call Error (1)');
		return array('status' => 'error', 'url' => $url, 'error_msg' => json_encode($error_msg));
	}

	if (substr($http_status,0,1) == 2) {
		error_log('Call success');
		return $result;
	} else {
		error_log('Call Error (2)');
		error_log('HTTP Status Code: ' . $http_status);
	}
}