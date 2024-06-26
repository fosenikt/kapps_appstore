<?php
namespace Kapps\Model\Auth;

use Kapps\Model\Database\Db;
use \Kapps\Model\Auth\Token as AuthToken;
use \Kapps\Model\Auth\Login;
use ReallySimpleJWT\TokenBuilder;
use ReallySimpleJWT\Token;




/**
 * Local login auth
 */
class O365Auth
{
	private $db;
	private $Login;
	private $Utils;
	
	public function __construct()
	{
		$this->db = Db::getInstance();
		$this->Login = new Login();
		$this->Utils = new Utils();
	}


	
	/**
	 * Auth user in local database
	 * 
	 * This function is used to auth user in the local database,
	 * with credentials received from O365 Login.
	 * 
     * @author     Robert Andresen <ra@fosenikt.no>
     * @copyright  2020 Fosen IKT
     * @license    MIT
	 * @since      v1.0
	 * 
	 * @param    Array    			$profile    Profile-data from O365
	 * @param    Array    			$tokens     O365 tokens
	 * @return   Boolean/String    ($token)		If valid login, a JWT token will be returned. Else it will return false.
	 */
	public function auth_o365_user($profile, $tokens)
	{
		// Check if user is found by O365 ID
		$query = "SELECT id FROM users WHERE o365_id LIKE '{$profile['id']}' OR mail LIKE '{$profile['userPrincipalName']}'";
		$result = $this->db->query($query);

		if (!$result) {
			error_log('Query to check for existing user failed');
			error_log($this->db->error);
		}

		if ($result->num_rows == 0) {
			$user_create = $this->create_user($profile);
			if (is_array($user_create) && $user_create['status'] == 'success') {
				$user_id = $user_create['id'];
			} else {
				error_log('Error creating user');
				error_log(json_encode($user_create));
				return false;
			}
		}

		else {
			$user_data = $result->fetch_array();
			$user_id = $user_data['id'];

			// If userID somehow could not be fetched, return false
			if (empty($user_id)) {
				return false;
			}

			// Update user profile from O365 to local DB
			$update_user = $this->update_user($profile);
		}



		// Create a JWT token
		$payload['user_id'] = $user_id;
		$token = $this->Utils->create_token($payload);

		// Set O365 token in local DB
		//$query = "UPDATE users SET o365_token='". json_encode($tokens) ."', customer_id='{$customer['id']}' WHERE id='$user_id'";
		//$result = $db->query($query);

		// Add event
		$query = "INSERT INTO event SET user_id='$user_id', domain='Auth', event_type='GuiLogin'";
		$result = $this->db->query($query);

		return $token;
	}


	/**
	 * Update users profile in local database
	 * 
     * @author     Robert Andresen <ra@fosenikt.no>
     * @copyright  2020 Fosen IKT
     * @license    MIT
	 * @since      v1.0
	 * 
	 * @param    Array    $profile    User-profile from O365
	 */
	public function update_user($profile)
	{
		if (empty($profile['id'])) {
			return false;
		}

		$query = "UPDATE users SET o365_id='{$profile['id']}', firstname='{$profile['givenName']}', lastname='{$profile['surname']}' WHERE o365_id='{$profile['id']}'";
		$result = $this->db->query($query);

		if ($result) {
			return true;
		} else {
			return false;
		}
	}





	/**
	 * Create user
	 * If user has a valid domain when login, but no user => Auto-create user
	 * 
	 * @param   String   $mail   Users e-mail
	 * @return  Array            Status-array
	 */
	private function create_user($profile)
	{
		$status = 'active';


		$get_company = $this->Login->get_company_by_mail($profile['userPrincipalName']);
		if ($get_company == null) {
			return array('status' => 'error', 'No company matching users e-mail');
		} else {
			$company_id = $get_company['id'];
		}

		$status = 'active';

		$stmt = $this->db->prepare("INSERT INTO users SET o365_id=?, customer_id=?, firstname=?, lastname=?, mail=?, mobile=?, company_role=?, status=?");
		if ($stmt === false) {
			return array('status' => 'error', 'error' => $this->db->error);
		}

		$result = $stmt->bind_param("sissssss", $profile['id'], $company_id, $profile['givenName'], $profile['lastname'], $profile['userPrincipalName'], $profile['mobilePhone'], $profile['jobTitle'], $status);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute();

		if ($result) {
			return array('status' => 'success', 'id' => $stmt->insert_id);
		} else {
			return array('status' => 'error', 'error' => $stmt->error);
		}

		$stmt->close();
	}





	/**
	 * User logout
	 * 
	 * @todo This only removes the sessions. Need to remove session in database
	 * 
     * @author     Robert Andresen <ra@fosenikt.no>
     * @copyright  2020 Fosen IKT
     * @license    MIT
	 * @since      v1.0
	 */
	public function logout()
	{
		unset($_SESSION['token_bearer']);
		unset($_SESSION['LOGGED_IN_USER_ID']);

		error_log('User logged out. Sessions destroyed.');

		return array('status' => 'success');

	}

}