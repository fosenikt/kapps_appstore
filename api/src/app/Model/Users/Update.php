<?php
namespace Kapps\Model\Users;

use Kapps\Model\Database\Db;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Update
{
	private $db;
	private $AuthUser;
	private $me;

	public function __construct()
	{
		$this->db = Db::getInstance();
		$this->AuthUser = new AuthUser;
		$this->me = $this->AuthUser->me();
	}






	/**
	 * Update user
	 * 
	 * Security: Checks if user is admin
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	Int      $id    App ID
	 * @return 	Array    $r     Array with app-data (see output)
	 */
	public function update_user($p)
	{

		if ($this->me['admin'] != 1) {
			return array('status' => 'error', 'message' => 'No access');
		}

		if (isset($p['id']) && !empty($p['id'])) {
			$id = $p['id'];
		} else {
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
		} else {
			$mail = '';
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

		if (isset($p['status']) && !empty($p['status'])) {
			$status = $p['status'];
		} else {
			return array('status' => 'error', 'message' => 'User-status not provided');
		}

		if (isset($p['customer_id']) && !empty($p['customer_id'])) {
			$customer_id = $p['customer_id'];
		} else {
			$customer_id = null;
		}

		if (isset($p['admin']) && !empty($p['admin'])) {
			$admin = $p['admin'];
		} else {
			$admin = null;
		}

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('UPDATE users SET customer_id=?, firstname=?, lastname=?, mail=?, mobile=?, company_role=?, status=?, admin=? WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('issssssii', $customer_id, $firstname, $lastname, $mail, $mobile, $company_role, $status, $admin, $id);
			//$stmt->execute();

			if (!$stmt) {
				return array('status' => 'error', 'message' => $this->db->error);
			}

			if (!$stmt->execute()) {
				return array('status' => 'error', 'message' => $stmt->error);
			}
		}

		if($stmt->affected_rows == 1) {
			$status = array('status' => 'success', 'id' => $id);
		} else {
			$status = array('status' => 'success', 'message' => 'No affected rows', 'id' => $id);
		}

		$stmt->close();
		return $status;
	}






	/**
	 * User update profile
	 */
	public function update_profile($p)
	{

		$user_id = $this->me['id'];
		$current_mail = $this->me['mail'];

		/* if ($this->me['admin'] != 1) {
			return array('status' => 'error', 'message' => 'No access');
		} */

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
				//http_response_code(400);
				return array('status' => 'error', 'message' => "$mail er en invalid e-postadresse");
			}

			// Check if domain is not changed
			list($new_mailuser, $new_maildomain) = explode('@', $p['mail']);
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


		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('UPDATE users SET firstname=?, lastname=?, mail=?, mobile=?, company_role=? WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('sssssi', $firstname, $lastname, $mail, $mobile, $company_role, $user_id);
			//$stmt->execute();

			if (!$stmt) {
				return array('status' => 'error', 'message' => $this->db->error);
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

?>