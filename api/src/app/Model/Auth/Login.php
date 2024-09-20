<?php
namespace Kapps\Model\Auth;

use Kapps\Model\Database\Db;
use \Kapps\Model\Mail\Send;
use \Kapps\Model\Auth\Utils;

/**
 * summary
 */
class Login
{

	private $db;
	private $Send;
	private $Utils;

	public function __construct()
	{
		$this->db = Db::getInstance();

		$this->Send = new Send();
		$this->Utils = new Utils();
	}




	/**
	 * Send login link
	 * Sends a code and hash to users e-mail to login
	 * 
	 * @param   String   $mail   Users e-mail
	 * @return  Array            Status-array
	 */
	public function send_login_link($mail)
	{

		// Check if e-mail has a valid format
		if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
			return array('status' => 'error', 'message' => 'Manglende eller ugyldig e-postadresse');
		}

		// Check if email has a valid domain
		if (!$this->validate_domain($mail)) {
			return array('status' => 'error', 'message' => 'Ugyldig domene');
		}

		

		// Get and/or validate user
		// Create user if mail-domain is valid and user does not exist
		$get_user = $this->get_user_id_by_mail($mail);

		if ($get_user == false) {
			$create_user = $this->create_user($mail);

			if (!isset($create_user['id']) || empty($create_user['id'])) {
				return array('status' => 'error', 'message' => 'Could not create user in database', 'create_response' => $create_user);
			}

			$get_user = array(
				'id' => $create_user['id'],
				'mail' => $mail,
				'status' => 'active'
			);
		}



		// Get and store login-code
		// Return error if no code is generated
		$login_code = $this->create_login_code($get_user['id']);

		if (!isset($login_code['pin_code']) || empty($login_code['pin_code'])) {
			return array('status' => 'error', 'message' => 'Could not generate login-code');
		}



		// Compose email
		$login_link = 'https://'.FRONTEND_HOST.'/login/';
		$subject = 'Innloggingkode for Kapps.no';
		$body = "Hei<br />
				 <p>Skriv inn f&oslash;lgende innloggingskode:</p>
				 <div style=\"font-size:25px\">{$login_code['pin_code']}</div>
				 <p>
				 	Eller trykk p&aring; lenken:
					 <a href=\"$login_link?u={$get_user['id']}&c={$login_code['hash']}\">$login_link?u={$get_user['id']}&c={$login_code['hash']}</a>
				 </p>
				 
				 <p>
					<b>Med vennlig hilsen</b><br />
					Kapps.no
				 </p>

				 <div style=\"font-size:11px\">
					<p><i>
						Dette er sendt til deg for at din e-postadressen ble oppgitt p&aring; Kapps.no sin innloggingsside.
						Kapps.no er en \"appstore\" med pekere til &aring;pne applikasjoner og tjenester for offentlig virksomheter.
					</i></p>
				 </div>
				 ";

		// Insert e-mail to database for schedule sending
		$send_mail = $this->Send->insert_mail($mail, $subject, $body);

		if (isset($send_mail['status']) && $send_mail['status'] == 'success') {
			$send_mail_status = true;
		} else {
			$send_mail_status = false;
		}


		// Return status
		if ($send_mail['status'] == 'success') {
			return array('status' => 'success', 'send_mail' => $send_mail_status);
		} else {
			return array('status' => 'error', 'message' => 'Could not store email for sending', 'send_mail' => $send_mail_status);
		}
	}




	/**
	 * Validate code users enters upon login
	 * 
	 * @param   String   $mail   Users e-mail
	 * @param   Int      $code   Login code
	 * @return  Boolean          True or false
	 */
	public function validate_code($mail, $code)
	{
		// Check if e-mail has a valid format
		if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
			return array('status' => 'error', 'message' => 'Manglende eller ugyldig e-postadresse');
		}



		$get_user = $this->get_user_id_by_mail($mail);
		$user_id = $get_user['id'];

		if (empty($user_id)) {
			return array('status' => 'error', 'message' => 'Could not find user ID from mail');
		}




		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('SELECT user_id FROM login_keys WHERE user_id=? AND pincode=? LIMIT 1')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('si', $user_id, $code);
			$stmt->execute();
			
			// Store the result so we can check if the account exists in the database.
			$stmt->store_result();
		}


		if($stmt->num_rows == 1) {
			
			// Delete old login-codes
			$this->delete_users_login_codes($user_id);

			// Generate token
			$payload = array('user_id' => $user_id, 'mail' => $mail);
			$token = $this->Utils->create_token($payload);

			return array('status' => 'success', 'token' => $token);
		}
		
		return array('status' => 'error');
	}




	/**
	 * Validate hash
	 * Used to validate if user clicks on login-link in mail
	 * 
	 * @param   String   $user_id   Users e-mail
	 * @param   String   $hash      Random hash
	 * @return  Boolean             Status-array
	 */
	public function validate_hash($user_id, $hash)
	{
		if (empty($user_id) || empty($hash)) {
			return array('status' => 'error', 'message' => 'Inputs missing');
		}

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('SELECT user_id status FROM login_keys WHERE hash LIKE ? AND user_id=? LIMIT 1')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('si', $hash, $user_id);
			$stmt->execute();
			
			// Store the result so we can check if the account exists in the database.
			$stmt->store_result();
		}


		if($stmt->num_rows == 1) {
			
			// Delete old login-codes
			$this->delete_users_login_codes($user_id);

			// Generate token
			$payload = array('user_id' => $user_id);
			$token = $this->Utils->create_token($payload);

			return array('status' => 'success', 'token' => $token);
		}
		
		return array('status' => 'error');
	}




	/**
	 * Validate domain
	 * Checks if users is allowed to login with this mail-domain
	 * 
	 * @param   String   $mail   Users e-mail
	 * @return  Boolean          Status-array
	 */
	private function validate_domain($mail)
	{
		list($user, $domain) = explode('@', $mail);
		$parts = explode('.', $domain);
		$last_part = end($parts);

		$query = "SELECT domain FROM company WHERE (domain LIKE '$domain' OR domain LIKE '$last_part')";
		$result = $this->db->query($query);

		if ($result->num_rows == 0) {
			return false;
		} else {
			return true;
		}
	}




	/**
	 * Create user
	 * If user has a valid domain when login, but no user => Auto-create user
	 * 
	 * @param   String   $mail   Users e-mail
	 * @return  Array            Status-array
	 */
	private function create_user($mail)
	{
		$this->db->set_charset("utf8mb4");
		$this->db->query("SET NAMES 'utf8mb4'");


		$get_company = $this->get_company_by_mail($mail);
		if ($get_company == null) {
			return array('status' => 'error', 'No company matching users e-mail');
		} else {
			$company_id = $get_company['id'];
		}

		$status = 'active';

		$stmt = $this->db->prepare("INSERT INTO users SET customer_id=?, mail=?, status=?");
		if ($stmt === false) {
			return array('status' => 'error', 'error' => $this->db->error);
		}

		$result = $stmt->bind_param("iss", $company_id, $mail, $status);

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
	 * Get user ID by mail
	 * Get the users incremental ID by mail
	 * 
	 * @param   String   $mail   Users e-mail
	 * @return  Array            Simple user-array
	 */
	private function get_user_id_by_mail($mail)
	{
		$user_id = 0;
		$status = '';

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('SELECT id, mail, status FROM users WHERE mail LIKE ? LIMIT 1')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('s', $mail);
			$stmt->execute();
			
			// Store the result so we can check if the account exists in the database.
			$stmt->store_result();
		}


		if($stmt->num_rows == 1) {
			$stmt->bind_result($user_id, $mail, $status);
			$stmt->fetch();
			$stmt->close();

			if ($status != 'active') {
				return false;
			}

			return array(
				'id' => $user_id,
				'mail' => $mail,
				'status' => $status,
			);
		} else {
			error_log('Did not find any user at login');
			return false;
		}
	}




	/**
	 * Get company ID by mail
	 * 
	 * @param   String   $mail   Users e-mail
	 * @return  Array            Simple company-array
	 */
	public function get_company_by_mail($mail)
	{

		$id = 0;
		$public_id = '';
		$title = '';
		$logo = '';
		$access = '';

		list($user, $domain) = explode('@', $mail);
		$parts = explode('.', $domain);
		//$last_part = end($parts);


		

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('SELECT id, public_id, domain, title, logo, access FROM company WHERE domain LIKE ? LIMIT 1')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('s', $domain);
			$stmt->execute();
			
			// Store the result so we can check if the account exists in the database.
			$stmt->store_result();
		}


		if($stmt->num_rows == 1) {
			$stmt->bind_result($id, $public_id, $domain, $title, $logo, $access);
			$stmt->fetch();
			$stmt->close();

			return array(
				'id' => $id,
				'public_id' => $public_id,
				'domain' => $domain,
				'title' => $title,
				'logo' => $logo,
				'access' => $access,
			);
		}

		return null;
	}




	/**
	 * Create a random logincode and hash,
	 * that are store in database for later check and
	 * are sent to the user
	 * 
	 * @param   String   $user_id   Users ID
	 * @return  Array               Status-array
	 */
	private function create_login_code($user_id)
	{
		if (empty($user_id)) {
			return array('status' => 'error', 'message' => 'User ID missing');
		}


		// Delete users old login codes
		$this->delete_users_login_codes($user_id);


		$expires = new \DateTime();
		$expires->modify('+ 1 hour');
		$time_expires = $expires->format('Y-m-d H:i:s');

		$pin_code = rand(11111, 99999);
		$hash = hash('sha256', $this->generateRandomString(15));

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('INSERT INTO login_keys SET time_expires=?, user_id=?, pincode=?, hash=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('siis', $time_expires, $user_id, $pin_code, $hash);
			
			if (!$stmt) {
				return array('status' => 'error', 'message' => $this->db->error);
			}

			if (!$stmt->execute()) {
				return array('status' => 'error', 'message' => $stmt->error);
			}
		}



		if($stmt->affected_rows == 1) {
			$stmt->close();
			return array('status' => 'success', 'pin_code' => $pin_code, 'hash' => $hash);
		} else {
			return array('status' => 'error');
		}
	}




	/**
	 * Delete user login codes
	 * Used before sending a new code and on successful login
	 * 
	 * @param   String   $user_id   Users ID
	 * @return  Array               Status-array
	 */
	private function delete_users_login_codes($user_id)
	{
		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('DELETE FROM login_keys WHERE user_id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $user_id);
			
			if (!$stmt) {
				return array('status' => 'error', 'message' => $this->db->error);
			}

			if (!$stmt->execute()) {
				return array('status' => 'error', 'message' => $stmt->error);
			}
		}



		if($stmt->affected_rows > 0) {
			$stmt->close();
			return array('status' => 'success');
		}
		
		return array('status' => 'error');
	}






	/**
	 * Delete expired login codes
	 * 
	 * @return  Array               Status-array
	 */
	private function delete_expired_login_codes()
	{
		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('DELETE FROM login_keys WHERE time_expires < NOW()')) {			
			if (!$stmt) {
				return array('status' => 'error', 'message' => $this->db->error);
			}

			if (!$stmt->execute()) {
				return array('status' => 'error', 'message' => $stmt->error);
			}
		}

		if($stmt->affected_rows > 1) {
			$stmt->close();
			return array('status' => 'success');
		}
		
		return array('status' => 'error');
	}




	/**
	 * Generate random string like password or just to hash something
	 */
	private function generateRandomString($length = 8) {
		$characters = '0123456789abcdefghijklmnopqrs092u3tuvwxyzaskdhfhf9882323ABCDEFGHIJKLMNksadf9044OPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}