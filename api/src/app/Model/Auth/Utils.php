<?php
namespace Kapps\Model\Auth;

use \Kapps\Model\General\Db;
use \Kapps\Model\General\Session;
use ReallySimpleJWT\TokenBuilder;
use ReallySimpleJWT\Token;

/**
 * summary
 */
class Utils extends Db
{

	private $token_expires = 48; // Hours 
	private $token_renew_expires = 120; // Minutes


	/**
	 * summary
	 */
	public function __construct() {}




	public function create_token($payload, $expires=0, $title='')
	{
		$days365 = 31536000;
		$hours48 = 172800;
		$hours24 = 86400;

		$secret = JWT_SECRET;
		//$expiration = time() + $hours48;
		if ($expires == 0) {
			$expiration = time() + ($this->token_expires * 60 * 60);
		} else {
			$expiration = time() + $expires;
		}
		$issuer = JWT_ISSUER;


		$user_id = $payload['user_id'];

		$token = Token::create($user_id, JWT_SECRET, $expiration, $issuer);

		$this->store_user_session($user_id, $token, date('Y-m-d H:i:s', $expiration), $title);

		// Add event
		/* $this->add(array(
			'domain' => 'Auth',
			'user_id' => $payload,
			'event_type' => 'CreateToken',
			'entity_id' => $payload,
			'event_data' => array('token' => '...'.substr($token, -7), 'expires' => $expiration),
		)); */

		return $token;
	}



	public function renew_token($token, $payload)
	{

		$token_payload = Token::getPayload($token, JWT_SECRET);

		$time_since = (time() - $token_payload['exp']) / 60;

		if ($time_since > $this->$token_renew_expires) {
			// Add event
			$this->add(array(
				'domain' => 'Auth',
				'user_id' => $payload,
				'event_type' => 'RenewTokenFailed',
				'entity_id' => $payload
			));

			return array('status' => 'error', 'message' => 'Token expired to long ago');
		}

		// Add event
		$this->add(array(
			'domain' => 'Auth',
			'user_id' => $payload,
			'event_type' => 'RenewToken',
			'entity_id' => $payload
		));

		return $this->create_token($payload);
	}



	/** 
	 * Get header Authorization
	 * */
	function getAuthorizationHeader(){
			$headers = null;
			if (isset($_SERVER['Authorization'])) {
				$headers = trim($_SERVER["Authorization"]);
			}
			else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
				$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
			} elseif (function_exists('apache_request_headers')) {
				$requestHeaders = apache_request_headers();
				// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
				$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
				//print_r($requestHeaders);
				if (isset($requestHeaders['Authorization'])) {
					$headers = trim($requestHeaders['Authorization']);
				}
			}
			return $headers;
		}


	/**
	 * get access token from header
	 * */
	function getBearerToken() {
		$headers = $this->getAuthorizationHeader();
		// HEADER: Get the access token from the header
		if (!empty($headers)) {
			if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
				return $matches[1];
			}
		}
		return null;
	}




	private function store_user_session($payload, $token, $expires, $title='')
	{

		$user_id = $payload;

		if (empty($user_id)) {
			error_log('Token: Could not store user session in database. Empty or no user parameter provided.');
			return array('status' => 'error', 'message' => 'missing_parameter', 'error' => 'No user ID provided');
		}

		elseif (empty($token)) {
			error_log('Token: Could not store user session in database. Empty or no token parameter provided.');
			return array('status' => 'error', 'message' => 'missing_parameter', 'error' => 'No token provided');
		}

		elseif (empty($expires)) {
			error_log('Token: Could not store user session in database. Empty or no expire parameter provided.');
			return array('status' => 'error', 'message' => 'missing_parameter', 'error' => 'No expire provided');
		}




		if (isset($_SERVER['HTTP_X_REAL_IP']) && !empty($_SERVER['HTTP_X_REAL_IP'])) {
			$ip_address = $_SERVER['HTTP_X_REAL_IP'];
		} else {
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}

		$query = "INSERT INTO user_sessions SET
					user_id='$user_id',
					title='$title',
					token='$token',
					time_expires='$expires',
					user_agent='{$_SERVER['HTTP_USER_AGENT']}', 
					ip_address='$ip_address'";
		$db = Db::getInstance();
		$result = $db->query($query);

		if ($result) {
			error_log('Token: Stored in user_sessions for user: ' . $user_id);
			return array('status' => 'success');
		} else {
			error_log('Token: Could not store user session in database, for userID: ' . $user_id);
			return array('status' => 'error', 'message' => 'db_error', 'error' => $db->error);
		}
	}




	private function add($p)
	{

		if (isset($p['department_id'])) {
			$department_id = $p['department_id'];
		} else {
			$department_id = 0;
		}

		if (isset($p['user_id'])) {
			$user_id = $p['user_id'];
		} else {
			//$user_id = $this->thisUser['id'];
			$user_id = 0;
		}

		if (isset($p['domain'])) {
			$domain = $p['domain'];
		} else {
			$domain = '';
		}

		if (isset($p['event_type'])) {
			$event_type = $p['event_type'];
		} else {
			$event_type = '';
		}

		if (isset($p['entity_id'])) {
			$entity_id = $p['entity_id'];
		} else {
			$entity_id = '';
		}

		if (isset($p['entity_tag'])) {
			$entity_tag = $p['entity_tag'];
		} else {
			$entity_tag = '';
		}

		if (isset($p['event_data'])) {
			$event_data = json_encode($p['event_data'], JSON_UNESCAPED_UNICODE);
		} else {
			$event_data = '';
		}

		$db = Db::getInstance();



		$db->set_charset("utf8mb4");
		$db->query("SET NAMES 'utf8mb4'");

		$stmt = $db->prepare("INSERT INTO event SET 
				department_id=?, user_id=?, domain=?, event_type=?, entity_id=?, entity_tag=?, event_data=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($db->error, E_USER_ERROR);
			return;
		}

		$result = $stmt->bind_param("iississ", $department_id, $user_id, $domain, $event_type, $entity_id, $entity_tag, $event_data);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute();

		if ($result) {
			//error_log('Event logged');
		} else {
			error_log('Error while logging event');
			error_log($stmt->error);
		}

		$stmt->close();
	}
}