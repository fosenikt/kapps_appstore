<?php
namespace Kapps\Model\Auth;

use \Kapps\Model\General\Db;
use \Kapps\Model\Auth\Utils;
use ReallySimpleJWT\Token;

/**
 * summary
 */
class User extends Db
{

	public function __construct()
	{
	}


	public function me()
	{
		$r = array();

		// Get token
		$objTools = new Utils;
		$get_token = $objTools->getBearerToken();

		if (empty($get_token) || $get_token == 'null') {
			error_log('No token provided');
			return array();
		}



		if (!$r) {

			// Validate token
			$validate_token = Token::validate($get_token, JWT_SECRET);

			if (!$validate_token) {
				error_log('Provided token: ' . $get_token);
				error_log('Invalid token. Cant get payload for user.');
				return array();
			}


			// Get token payload
			$token_payload = Token::getPayload($get_token, JWT_SECRET);
			$user_id = $token_payload['user_id'];



			if (empty($user_id)) {
				error_log('No userID found in token payload');
				return array();
			}


			// Query user
			$query = "SELECT id, mail, customer_id, firstname, lastname, initials, mobile, company_role, admin FROM users WHERE id LIKE '$user_id' LIMIT 1";
			$db = Db::getInstance();
			$result = $db->query($query);
			$numRows = $result->num_rows;

			if ($numRows > 0) {
				$row = $result->fetch_array();


				// Logged in user is queried by many classes... Switching customer causes mysql to crash with to many connections.
				//error_log('get_loggedin_user 1');
				if (empty($row['customer_id'])) {
					$customer_id = $this->get_user_random_customer($row['id']);
				} else {
					$customer_id = $row['customer_id'];
				}

				$r = array(
					'id' => $row['id'],
					'mail' => $row['mail'],
					'customer' => $this->get_my_company($customer_id),
					'firstname' => $row['firstname'],
					'lastname' => $row['lastname'],
					'initials' => $row['initials'],
					'mobile' => $row['mobile'],
					'company_role' => $row['company_role'],
					'admin' => $row['admin'],
				);

				
			} else {
				error_log('No user found in database with userID: ' . $user_id . '. Maybe deleted or deactivated?');
				$r = array('status' => 'error', 'message' => 'Could not find user or validate token');
			}
		}


		return $r;
	}





	public function validate_login()
	{
		if (isset($_SESSION['user_validated']) && $_SESSION['user_validated'] > 0) {
			error_log('User validated by PHPSESS');
			return array('status' => 'success');
		}


		// Get token
		$objTools = new Utils;
		$get_token = $objTools->getBearerToken();

		if (empty($get_token) || $get_token == 'null') {
			return array('status' => 'error', 'message' => 'No token provided in request');
		}




		// Validate token
		$validate_token = Token::validate($get_token, JWT_SECRET);

		if (!$validate_token) {
			return array('status' => 'error', 'message' => 'Invalid token. Token most likely expired or signed out.');
		}


		// Get token payload
		$token_payload = Token::getPayload($get_token, JWT_SECRET);
		$user_id = $token_payload['user_id'];

		if (empty($user_id)) {
			return array('status' => 'error', 'message' => 'No userID found in token payload');
		}


		// Validate user in local database
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare("SELECT id, status FROM users WHERE id=?")) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $user_id);
			$stmt->execute();
		}

		// If user not found in local database
		if($stmt->affected_rows == 0) {
			return array('status' => 'error', 'message' => 'Could not find user');
		}


		// Fetch status for user. Return error if user is deactivated
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();

		if($user['status'] == 'deactivated') {
			return array('status' => 'error', 'message' => 'User is deactivated');
		}


		// If all checks are passed, return true.
		$_SESSION['user_validated'] = $user_id;
		return array('status' => 'success');
	}





	public function get_my_company($id)
	{
		$r = array();

		$query = "SELECT * FROM company WHERE id='$id'";
		$db = Db::getInstance();
		$result = $db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r = array(
					'public_id' => $row['public_id'],
					'domain' => $row['domain'],
					'title' => $row['title'],
					'logo' => $row['logo'],
				);
			}
		}

		return $r;
	}






	public function signout()
	{
		$user = $this->me();
		$user_id = $user['id'];

		

		// Create DB instance
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('DELETE FROM user_sessions WHERE user_id=? AND token=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('is', $user_id, $get_token);
			
			if (!$stmt) {
				return array('status' => 'error', 'message' => $db->error);
			}

			if (!$stmt->execute()) {
				return array('status' => 'error', 'message' => $stmt->error);
			}
		}



		if($stmt->affected_rows > 0) {
			$stmt->close();

			// Remove session
			unset($_SESSION['user_validated']);

			// Return status
			return array('status' => 'success');
		}
		
		return array('status' => 'error');
	}
}