<?php
require_once('../../config.php');
require_once('../../app/autoload.php');
require_once('../../vendor/autoload.php');

if (isset($_GET['redir'])) {
	error_log($_GET['redir']);
	$_SESSION['redir'] = $_GET['redir'];

	error_log('Session redir #1: ' . $_SESSION['redir']);
}

$authController = new Kapps\Model\Auth\Microsoft\AuthController();
$signin = $authController->signin();

if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
	$_SESSION['LOGIN_REFERER'] = $_SERVER['HTTP_REFERER'];
}

//header('Location: ' . $signin . '&redir=' . $_SERVER['HTTP_REFERER']);
error_log("Redirect to $signin");
header('Location: ' . $signin);