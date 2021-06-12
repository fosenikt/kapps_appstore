<?php
require_once('functions.php');

$api_base_url = 'https://'.getenv("API_URL");
$api_token = getenv("TOKEN");

$schedule = CallAPI('GET', '/mail/send', false);