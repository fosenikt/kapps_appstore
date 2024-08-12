<?php
namespace Kapps\Model\Auth;

use Kapps\Model\Database\Db;
use \Kapps\Model\Auth\Utils;
use ReallySimpleJWT\Token;

/**
 * summary
 */
class User
{
	private $db;

	public function __construct()
	{
		$this->db = Db::getInstance();
	}


	public function isAuthenticated()
    {
        return $this->validate_login();
    }



	public function me()
	{
		$r = array();

		// Get token
		$objTools = new Utils;
		$get_token = $objTools->getBearerToken();

		if (empty($get_token) || $get_token == 'null') {
			error_log('No token provided');
			//http_response_code(401);
			return array();
		}



		if (!$r) {

			// Validate token
			$validate_token = Token::validate($get_token, JWT_SECRET);

			if (!$validate_token) {
				error_log('Provided token: ' . $get_token);
				error_log('Invalid token. Cant get payload for user.');
				http_response_code(401);
				return array();
			}


			// Get token payload
			$token_payload = Token::getPayload($get_token, JWT_SECRET);
			$user_id = $token_payload['user_id'];



			if (empty($user_id)) {
				error_log('No userID found in token payload');
				http_response_code(401);
				return array();
			}


			// Query user
			$query = "SELECT id, mail, customer_id, firstname, lastname, initials, mobile, company_role, admin FROM users WHERE id LIKE '$user_id' LIMIT 1";
			$result = $this->db->query($query);
			$numRows = $result->num_rows;

			if ($numRows > 0) {
				$row = $result->fetch_array();
				// Logged in user is queried by many classes... Switching customer causes mysql to crash with to many connections.
				if (empty($row['customer_id'])) {
					$get_customer = null;
				} else {
					$customer_id = $row['customer_id'];
					$get_customer = $this->get_my_company($customer_id);
				}

				$r = array(
					'id' => $row['id'],
					'mail' => $row['mail'],
					'customer' => $get_customer,
					'firstname' => $row['firstname'],
					'lastname' => $row['lastname'],
					'initials' => $row['initials'],
					'mobile' => $row['mobile'],
					'company_role' => $row['company_role'],
					'admin' => $row['admin'],
				);

				
			} else {
				error_log('No user found in database with userID: ' . $user_id . '. Maybe deleted or deactivated?');
				http_response_code(404);
				$r = array('status' => 'error', 'message' => 'Could not find user or validate token');
			}
		}


		return $r;
	}





	public function validate_login()
	{
		// Get token
		$objTools = new Utils;
		$get_token = $objTools->getBearerToken();

		if (empty($get_token) || $get_token == 'null') {
			error_log('No token provided in request');
			//return array('status' => 'error', 'message' => 'No token provided in request');
			return false;
		}




		// Validate token
		$validate_token = Token::validate($get_token, JWT_SECRET);

		if (!$validate_token) {
			error_log('Invalid token. Token most likely expired or signed out.');
			//return array('status' => 'error', 'message' => 'Invalid token. Token most likely expired or signed out.');
			return false;
		}


		// Get token payload
		$token_payload = Token::getPayload($get_token, JWT_SECRET);
		$user_id = $token_payload['user_id'];

		if (empty($user_id)) {
			error_log('No userID found in token payload');
			//return array('status' => 'error', 'message' => 'No userID found in token payload');
			return false;
		}


		// Validate user in local database
		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare("SELECT id, status FROM users WHERE id=?")) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $user_id);
			$stmt->execute();
		}

		// If user not found in local database
		if($stmt->affected_rows == 0) {
			error_log('Could not find user');
			//return array('status' => 'error', 'message' => 'Could not find user');
			return false;
		}


		// Fetch status for user. Return error if user is deactivated
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();

		if($user['status'] == 'deactivated') {
			error_log('User is deactivated');
			//return array('status' => 'error', 'message' => 'User is deactivated');
			return false;
		}


		// If all checks are passed, return true.
		//$_SESSION['user_validated'] = $user_id;
		//return array('status' => 'success');
		return true;
	}





	public function get_my_company($id)
	{
		$r = array();

		$query = "SELECT * FROM company WHERE id='$id'";
		$result = $this->db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r = array(
					'public_id' => $row['public_id'],
					'domain' => $row['domain'],
					'title' => $row['title'],
					'logo' => $this->get_logo($row['logo']),
				);
			}
		}

		return $r;
	}



	private function get_logo($filename)
	{
		if (empty($filename)) {
			return array(
				'image' => '/assets/images/icons/building.svg',
				'thumb' => '/assets/images/icons/building.svg'
			);
		}

		$exp = explode('.', $filename);
		$ext = strtolower(end($exp));

		if (empty($filename)) {
			$logo = array(
				'image' => '/assets/images/icons/building.svg',
				'thumb' => '/assets/images/icons/building.svg'
			);
		} else {
			if ($ext != 'svg' && $ext != 'webp') {
				$logo = array(
					'image' => '//'.URL.'/data/companies_logo/'.$filename,
					'thumb' => '//'.URL.'/data/companies_logo/_thumbs/'.$filename
				);
			} else {
				$logo = array(
					'image' => '//'.URL.'/data/companies_logo/'.$filename,
					'thumb' => '//'.URL.'/data/companies_logo/'.$filename
				);
			}
		}

		return $logo;
	}






	public function signout()
	{
		$user = $this->me();
		$user_id = $user['id'];


		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('DELETE FROM user_sessions WHERE user_id=? AND token=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('is', $user_id, $get_token);
			
			if (!$stmt) {
				return array('status' => 'error', 'message' => $this->db->error);
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