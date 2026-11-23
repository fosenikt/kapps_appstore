<?php
require_once('config.php');
require_once('vendor/autoload.php');
require_once('./app/autoload.php');
require_once('./functions.php');

// Guard: database install/upgrade must never be triggerable by an anonymous visitor.
// Require an authenticated admin bearer token.
$me = (new \Kapps\Model\Auth\User())->me();
if (!is_array($me) || empty($me['admin']) || $me['admin'] != 1) {
	header('HTTP/1.0 403 Forbidden');
	header('Content-Type: application/json');
	echo json_encode(['status' => 'error', 'message' => 'Access denied']);
	exit;
}

//echo exec('whoami').'<br />';


$database = new FIP\Model\Install\Database;

if ($database->chk_db_upgrade() > 0) {
	$database->db_upgrade();
}