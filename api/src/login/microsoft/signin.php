<?php
require_once('../../config.php');
require_once('../../app/autoload.php');
require_once('../../vendor/autoload.php');

error_log($_GET['redir']);
$_SESSION['redir'] = $_GET['redir'];

error_log('Session redir #1: ' . $_SESSION['redir']);

$authController = new FIP\Model\Auth\Microsoft\AuthController();
$signin = $authController->signin();

$_SESSION['LOGIN_REFERER'] = $_SERVER['HTTP_REFERER'];

//header('Location: ' . $signin . '&redir=' . $_SERVER['HTTP_REFERER']);
header('Location: ' . $signin);