<?php
namespace Kapps\Model\Users;

use \Kapps\Model\General\Db;
use \Kapps\Model\General\Colors;
use \Kapps\Model\Companies\Companies;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Admin extends Db
{

	public function __construct()
	{
		$this->Companies = new Companies;
		$this->Colors = new Colors;

		$this->AuthUser = new AuthUser;
		$this->me = $this->AuthUser->me();
	}




	/**
	 * Create user
	 * 
	 * Security: Checks if user is admin
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	Int      $id    App ID
	 * @return 	Array    $r     Array with app-data (see output)
	 */
	public function create_user($p)
	{

		if ($this->me['admin'] != 1) {
			return array('status' => 'error', 'message' => 'No access');
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
			$status = '';
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


		// Create DB instance
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('INSERT INTO users SET customer_id=?, firstname=?, lastname=?, mail=?, mobile=?, company_role=?, status=?, admin=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('issssssi', $customer_id, $firstname, $lastname, $mail, $mobile, $company_role, $status, $admin);
			//$stmt->execute();

			if (!$stmt) {
				return array('status' => 'error', 'message' => $db->error);
			}

			if (!$stmt->execute()) {
				return array('status' => 'error', 'message' => $stmt->error);
			}
		}

		if($stmt->affected_rows == 1) {
			$status = array('status' => 'success', 'id' => $stmt->insert_id);
		} else {
			$status = array('status' => 'error', 'message' => 'Error creating user', 'msgid' => 2);
		}

		$stmt->close();
		return $status;

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


		// Create DB instance
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('UPDATE users SET customer_id=?, firstname=?, lastname=?, mail=?, mobile=?, company_role=?, status=?, admin=? WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('issssssii', $customer_id, $firstname, $lastname, $mail, $mobile, $company_role, $status, $admin, $id);
			//$stmt->execute();

			if (!$stmt) {
				return array('status' => 'error', 'message' => $db->error);
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
	 * Delete user
	 * 
	 * Security: Checks if user is admin
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	Int      $id    App ID
	 * @return 	Array    $r     Array with app-data (see output)
	 */
	public function delete_user($id)
	{

		if ($this->me['admin'] != 1) {
			return array('status' => 'error', 'message' => 'No access');
		}

		if (!isset($id) || empty($id)) {
			return array('status' => 'error', 'message' => 'User ID missing');
		}


		// Create DB instance
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('DELETE FROM users WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $id);
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
			$status = array('status' => 'error', 'message' => 'Error deleting user', 'msgid' => 2);
		}

		$stmt->close();
		return $status;
	}


}