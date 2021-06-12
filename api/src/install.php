<?php
require_once('config.php');
require_once('vendor/autoload.php');
require_once('./app/autoload.php');
require_once('./functions.php');

//echo exec('whoami').'<br />';


$database = new FIP\Model\Install\Database;

if ($database->chk_db_upgrade() > 0) {
	$database->db_upgrade();
}