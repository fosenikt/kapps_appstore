<?php
namespace Kapps\Model\Users;

use \Kapps\Model\General\Db;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class MyProfile extends Db
{

	public function __construct()
	{
		$this->AuthUser = new AuthUser;
		$this->me = $this->AuthUser->me();
	}


	public function get_my_profile()
	{
		$r = array();

		$query = "SELECT * FROM users";
		$db = Db::getInstance();
		$result = $db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r = array(
					'firstname' => $row['firstname'],
					'lastname' => $row['lastname'],
					'mail' => $row['mail'],
					'mobile' => $row['mobile'],
					'company_role' => $row['company_role'],
					'photo' => $row['photo'],
					'last_update' => $row['last_update'],
					'status' => $row['status'],
					'company' => $this->get_my_company($row['customer_id']),
				);
			}
		}

		return $r;
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






	public function update($p)
	{

		$user_id = $this->me['id'];
		$current_mail = $this->me['mail'];

		if ($this->me['admin'] != 1) {
			return array('status' => 'error', 'message' => 'No access');
		}

		if (!isset($user_id) || empty($user_id)) {
			return array('status' => 'error', 'message' => 'User ID missing');
		}


		if (isset($p['firstname']) && !empty($p['firstname'])) {
			$firstname = $p['firstname'];
		} else {
			$firstname = '';
		}

		if (isset($p['lastname']) && !empty($p['lastname'])) {
			$lastname = $p['lastname'];
		} else {
			$lastname = '';
		}

		if (isset($p['mail']) && !empty($p['mail'])) {
			$mail = $p['mail'];

			// Check if mail is valid
			if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
				return array('status' => 'error', 'message' => 'Invalid e-postadresse');
			}

			// Check if domain is not changed
			list($new_mailuser, $new_maildomain) = explode('@', $mail);
			list($current_mailuser, $current_maildomain) = explode('@', $current_mail);

			if ($new_maildomain != $current_maildomain) {
				return array('status' => 'error', 'message' => 'Du kan ikke endre e-postdomenet, da det er knyttet mot din virksomhet.');
			}

		} else {
			return array('status' => 'error', 'message' => 'E-postadresse mangler');
		}

		if (isset($p['mobile']) && !empty($p['mobile'])) {
			$mobile = $p['mobile'];
		} else {
			$mobile = '';
		}

		if (isset($p['company_role']) && !empty($p['company_role'])) {
			$company_role = $p['company_role'];
		} else {
			$company_role = '';
		}


		// Create DB instance
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('UPDATE users SET firstname=?, lastname=?, mail=?, mobile=?, company_role=? WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('sssssi', $firstname, $lastname, $mail, $mobile, $company_role, $user_id);
			//$stmt->execute();

			if (!$stmt) {
				return array('status' => 'error', 'message' => $db->error);
			}

			if (!$stmt->execute()) {
				return array('status' => 'error', 'message' => $stmt->error);
			}
		}

		if($stmt->affected_rows == 1) {
			$status = array('status' => 'success');
		} else {
			$status = array('status' => 'success', 'message' => 'Ingen endring');
		}

		$stmt->close();
		return $status;
	}


}