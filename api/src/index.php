<?php
require_once('config.php');
require_once('vendor/autoload.php');

cors();
header('Content-Type: application/json');

/**
 * @OA\Info(
 *     title="Kapps API",
 *     version="2.0.0",
 *     description="API for KommuneApplications (Kapps)"
 * )
 */


/**
 *  An example CORS-compliant method.  It will allow any GET, POST, or OPTIONS requests from any
 *  origin.
 *
 *  In a production environment, you probably want to be more restrictive, but this gives you
 *  the general idea of what is involved.  For the nitty-gritty low-down, read:
 *
 *  - https://developer.mozilla.org/en/HTTP_access_control
 *  - http://www.w3.org/TR/cors/
 *
 */
function cors() {
	// Allow from any origin
	if (isset($_SERVER['HTTP_ORIGIN'])) {
		// Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
		// you want to allow, and if so:
		header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		//header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400');    // cache for 1 day
		//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	}

	// Access-Control headers are received during OPTIONS requests
	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
			// may also be using PUT, PATCH, HEAD etc
			header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");         

		if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
			header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

		exit(0);
	}
}





// Routes
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {


	// Auth
	$r->addRoute('POST', '/auth/login/send', ['Kapps\Controller\Auth\Login', 'send_login_link']);
	$r->addRoute('POST', '/auth/login/validate/code', ['Kapps\Controller\Auth\Login', 'validate_code']);
	$r->addRoute('POST', '/auth/login/validate/hash', ['Kapps\Controller\Auth\Login', 'validate_hash']);
	$r->addRoute('GET', '/auth/login/signout', ['Kapps\Controller\Auth\User', 'signout']);
	$r->addRoute('GET', '/user/me', ['Kapps\Controller\Auth\User', 'me']);


	// Application
	$r->addRoute('GET', '/app/get/{id:\d+}', ['Kapps\Controller\Apps\Get', 'get_app']);
	$r->addRoute('GET', '/apps/get', ['Kapps\Controller\Apps\Get', 'get_apps']);
	$r->addRoute('POST', '/app/new', ['Kapps\Controller\Apps\Create', 'add']);
	$r->addRoute('POST', '/app/update/all', ['Kapps\Controller\Apps\Update', 'update_app']);
	$r->addRoute('POST', '/app/update/desc', ['Kapps\Controller\Apps\Update', 'update_description']);
	$r->addRoute('POST', '/app/update/install', ['Kapps\Controller\Apps\Update', 'update_installation']);
	$r->addRoute('POST', '/app/update/details', ['Kapps\Controller\Apps\Update', 'update_details']);
	$r->addRoute('GET', '/app/publish/{id:\d+}', ['Kapps\Controller\Apps\Update', 'publish']);
	$r->addRoute('GET', '/app/unpublish/{id:\d+}', ['Kapps\Controller\Apps\Update', 'unpublish']);
	$r->addRoute(['DELETE', 'GET'], '/app/delete/{id:\d+}', ['Kapps\Controller\Apps\Delete', 'delete']);

	// App: Companies
	$r->addRoute('GET', '/company/published/apps/{id}', ['Kapps\Controller\Apps\Get', 'get_company_published_apps']);
	$r->addRoute('GET', '/company/apps', ['Kapps\Controller\Apps\Get', 'get_company_apps']);
	$r->addRoute('GET', '/company/app/{id:\d+}', ['Kapps\Controller\Apps\Get', 'get_company_app']);




	// App: Images
	$r->addRoute('POST', '/app/images/upload', ['Kapps\Controller\Apps\Images', 'upload']);
	$r->addRoute('POST', '/app/images/delete', ['Kapps\Controller\Apps\Images', 'delete_image']);
	$r->addRoute('POST', '/app/image/primary/set', ['Kapps\Controller\Apps\Images', 'set_primary_image']);

	// App Files
	$r->addRoute('POST', '/app/files/upload', ['Kapps\Controller\Apps\Files', 'upload']);
	$r->addRoute('POST', '/app/file/delete', ['Kapps\Controller\Apps\Files', 'delete']);
	


	



	// Companies
	$r->addRoute('GET', '/companies/get', ['Kapps\Controller\Companies\Get', 'get_companies']);
	$r->addRoute('GET', '/companies/simplelist/get', ['Kapps\Controller\Get\Companies', 'get_companies_simple']);
	$r->addRoute('GET', '/company/get/{id}', ['Kapps\Controller\Companies\Get', 'get_company']);
	$r->addRoute('GET', '/company/counties/get', ['Kapps\Controller\Companies\Get', 'get_counties']);
	$r->addRoute('POST', '/company/create', ['Kapps\Controller\Companies\Create', 'create']);
	$r->addRoute('POST', '/company/update', ['Kapps\Controller\Companies\Update', 'update']);
	$r->addRoute(['DELETE', 'GET'], '/company/delete/{id}', ['Kapps\Controller\Companies\Delete', 'delete']);
	$r->addRoute('GET', '/companies/search/{q}', ['Kapps\Controller\Search\Search', 'companies_get']);
	
	$r->addRoute('POST', '/company/logo/upload', ['Kapps\Controller\Upload\Upload', 'upload_company_logo']);

	// Companies: Employees
	$r->addRoute('GET', '/company/employees/{company_id}', ['Kapps\Controller\Employees\Get', 'get_employees']);

	$r->addRoute('GET', '/company/municipalities/get', ['Kapps\Controller\Municipality\Get', 'get_municipalities']);



	// Type
	$r->addRoute('GET', '/types/get', ['Kapps\Controller\Types\Get', 'get_types']);


	// License
	$r->addRoute('GET', '/licenses/get', ['Kapps\Controller\Licenses\Get', 'get_licenses']);
	$r->addRoute('GET', '/license/get/{id:\d+}', ['Kapps\Controller\Licenses\Get', 'get_license']);


	// Delivery
	$r->addRoute('GET', '/delivery/methods/get', ['Kapps\Controller\Delivery\Methods', 'get_methods']);


	// Mail
	$r->addRoute('GET', '/mail/send', ['Kapps\Controller\Mail\Send', 'send']);


	// Search
	$r->addRoute('POST', '/search/all', ['Kapps\Controller\Search\Search', 'all']);
	$r->addRoute('POST', '/search/apps', ['Kapps\Controller\Search\Search', 'apps']);
	$r->addRoute('POST', '/search/companies', ['Kapps\Controller\Search\Search', 'companies']);
	$r->addRoute(['POST', 'GET'], '/search/users[/{q}]', ['Kapps\Controller\Search\Search', 'users']);
	
	// Stats
	$r->addRoute('POST', '/stats/log', ['Kapps\Controller\Stats\Log', 'log']);
	$r->addRoute('GET', '/stats/apps/count/published', ['Kapps\Controller\Stats\Stats', 'num_published']);
	$r->addRoute('GET', '/stats/apps/latest', ['Kapps\Controller\Stats\Stats', 'last_published']);
	$r->addRoute('GET', '/stats/apps/popular', ['Kapps\Controller\Stats\Stats', 'most_popular_apps']);



	// Users
	$r->addRoute('GET', '/users/get', ['Kapps\Controller\Users\Get', 'get_users']);
	$r->addRoute('GET', '/users/company/get/{id}', ['Kapps\Controller\Users\Get', 'get_company_users']);
	$r->addRoute('GET', '/user/get/{id:\d+}', ['Kapps\Controller\Users\Get', 'get_user']);
	
	$r->addRoute('POST', '/myprofile/update', ['Kapps\Controller\Users\Update', 'update_profile']);


	// Users: Admin
	$r->addRoute('POST', '/admin/user/create', ['Kapps\Controller\Users\Create', 'create_user']);
	$r->addRoute('POST', '/admin/user/update', ['Kapps\Controller\Users\Update', 'update_user']);
	$r->addRoute(['DELETE', 'GET'], '/admin/user/delete/{id:\d+}', ['Kapps\Controller\Users\Delete', 'delete_user']);

	// Users: Sessions/Tokens
	$r->addRoute('POST', '/admin/user/token/create', ['Kapps\Controller\Users\Tokens', 'create_token']);
	$r->addRoute('GET', '/admin/user/token/get/{id:\d+}', ['Kapps\Controller\Users\Tokens', 'get_tokens']);
	$r->addRoute(['DELETE', 'GET'], '/admin/user/token/delete/{id:\d+}', ['Kapps\Controller\Users\Tokens', 'delete_token']);
});



// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];



// Preflight OPTION check
// Just send OK to browser who to preflight with OPTIONS on cross-domain
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	error_log('Preflight');
	header("HTTP/1.0 200");
	return array('status' => 'success');
}



// Strip query string (?foo=bar) and decode URI
// Commented out and replaced with code below. Source: https://github.com/nikic/FastRoute/issues/19
if (false !== $pos = strpos($uri, '?')) {
	$uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);


switch ($routeInfo[0]) {
	case FastRoute\Dispatcher::NOT_FOUND:
		error_log('Route not found - ' . $uri);
		header("HTTP/1.0 404 Not Found");
		break;
	case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
		$allowedMethods = $routeInfo[1];
		error_log('allowedMethods: ' . json_encode($allowedMethods));
		header("HTTP/1.0 405 Method Not Allowed"); 
		break;
	case FastRoute\Dispatcher::FOUND:

		// Get class and method from route
		$className = $routeInfo[1][0];
		$method = $routeInfo[1][1];
		$vars = $routeInfo[2];

		// Get php://input
		$input = file_get_contents("php://input");
		$input = trim(preg_replace('/\s+/', ' ', $input));


		// Check if data in php://input exist
		if ($input) {			
			$temp = json_decode($input, true); // Decode input

			// Loop input to map them to POST
			if (is_array($temp) && count($temp) > 0) {
				foreach ($temp as $key => $value) {
					$_POST[$key] = $value;
				}
			} else {
				error_log('API HANDLER: No data in array');
			}
		}

		// Call class
		$result = call_user_func_array(array(new $className, $method), $vars);
		output($result);

		break;
}