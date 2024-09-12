<?php

/**
	Output function for handler
*/
function output($result)
{
	if (is_object($result) && get_class($result) === 'stdClass') {
		$result = (array)$result;
	}

	error_log(json_encode($result));

	if (isset($result['message']) && !empty($result['message'])) {
		$message = $result['message'];
	} else {
		$message = '';
	}

	if (isset($result['status']) || isset($result['status_code'])) {
		if (isset($result['status_code'])) {
			header("HTTP/1.0 " . $result['status_code'] . " " . $message); 
		}

		elseif ($result['status'] == 'error') {
			if (isset($result['message'])) {
				error_log('[ERROR] ' . $message);
			}
			header("HTTP/1.0 400 Method Not Allowed");
			header('Content-Type: application/json');
		}
	}
	//error_log('[Output] ' . json_encode($result));
	echo json_encode($result);
}