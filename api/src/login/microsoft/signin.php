<?php
require_once('../../config.php');
require_once('../../app/autoload.php');
require_once('../../vendor/autoload.php');

if (isset($_GET['redir'])) {
	$redir = (string)$_GET['redir'];

	// Open-redirect guard: only accept a same-site absolute path (e.g. "/apps/all").
	// Reject absolute URLs ("http://evil"), protocol-relative ("//evil"), backslash
	// tricks ("/\evil") and anything containing a scheme (":"). This also stops the
	// JWT (passed back on the login URL) from leaking to an attacker-controlled host.
	if ($redir === '' || $redir[0] !== '/' || strpos($redir, '//') === 0
		|| strpos($redir, '\\') !== false || strpos($redir, ':') !== false) {
		$redir = '';
	}

	$_SESSION['redir'] = $redir;
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