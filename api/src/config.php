<?php
ob_start();
session_start();


/** PHP settings */
//date_default_timezone_set('Europe/Oslo');
setlocale(LC_ALL, 'no_Nb');
//error_reporting(E_ALL ^ E_NOTICE);


/** Database */
if (empty(getenv("DB_NAME")) || empty(getenv("DB_USER")) || empty(getenv("DB_PASSWORD")) || empty(getenv("DB_HOST"))) {
	die('Database missing connection variables. Please check environment variables.');
}

define('DB_NAME', getenv("DB_NAME")); // Db name
define('DB_USER', getenv("DB_USER")); // User
define('DB_PASSWORD', getenv("DB_PASSWORD")); // Password
define('DB_HOST', getenv("DB_HOST")); // hostname
define('DB_PORT', getenv("DB_PORT")); // Port


/** Memcache server */
define('MEMCACHED_HOST', getenv("MEMCACHED_HOST"));
define('MEMCACHED_PORT', getenv("MEMCACHED_PORT"));

define('JWT_SECRET', getenv("JWT_SECRET"));
define('JWT_ISSUER', getenv("JWT_ISSUER"));

// IPGeolocation
// URL: https://app.ipgeolocation.io/
define('IPGEOLOCATION_API_KEY', getenv("IPGEOLOCATION_API_KEY"));



define('URL', getenv("VIRTUAL_HOST"));
define('FRONTEND_HOST', getenv("FRONTEND_HOST"));

if (getenv("WEB_ROOT") === false) {
	define('ABSPATH', '/var/www/html/');
} else {
	define('ABSPATH', getenv("WEB_ROOT"));
}



define('SMTP_HOST', getenv("SMTP_HOST"));
define('SMTP_USERNAME', getenv("SMTP_USERNAME"));
define('SMTP_PASSWORD', getenv("SMTP_PASSWORD"));
define('SMTP_PORT', getenv("SMTP_PORT"));
define('SMTP_SENDER_MAIL', getenv("SMTP_SENDER_MAIL"));
define('SMTP_SENDER_NAME', getenv("SMTP_SENDER_NAME"));

define('O365_TENANT_ID', getenv("O365_TENANT_ID"));
define('O365_APP_ID', getenv("O365_APP_ID"));
define('O365_APP_PASSWORD', getenv("O365_APP_SECRET"));
define('O365_REDIRECT_URI', 'https://'.getenv("VIRTUAL_HOST").'/login/microsoft/getToken.php');
define('O365_SCOPES', 'openid profile offline_access User.Read');
define('O365_AUTHORIZE_ENDPOINT', 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize');
define('O365_TOKEN_ENDPOINT', 'https://login.microsoftonline.com/common/oauth2/v2.0/token');