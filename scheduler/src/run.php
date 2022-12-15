<?php
require_once('functions.php');

if (getenv("SSL") !== null && getenv("SSL") == 'true') {
	$api_base_url = 'https://'.getenv("API_URL");
} else {
	$api_base_url = 'https://'.getenv("API_URL");
}
$api_token = getenv("TOKEN");

$schedule = CallAPI('GET', '/mail/send', false);