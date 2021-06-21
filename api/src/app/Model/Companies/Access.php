<?php
namespace Kapps\Model\Companies;

use \Kapps\Model\General\Db;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Access extends Db
{

	public function __construct()
	{
		$this->AuthUser = new AuthUser;
		$this->thisUser = $this->AuthUser->me();
	}





	/**
	 * Check if user has access to customer
	 * 
	 * @param    Int           $id              User ID
	 * @param    Boolean       $public_id       True if $id is public ID
	 * @return   Boolean                        True is admin, false is not
	 */
	public function chk_customer_access($id, $public_id=true)
	{

		// Fetch and check against public id
		if ($public_id) {
			if ($id == $this->thisUser['customer']['public_id']) {
				return true;
			}
		}

		// Check against customer ID
		else {
			if ($id == $this->publicId2Id($this->thisUser['customer']['public_id'])) {
				return true;
			}
		}


		// Check admin
		if ($this->isAdmin()) {
			return true;
		}


		return false;
	}





	/**
	 * Convert customer ID to public ID
	 * 
	 * @param    Int      $id          Customer ID
	 * @return   String   $public_id   Public Customer ID
	 */
	private function id2publicID($id)
	{
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('SELECT public_id FROM company WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $id);
			$stmt->execute();
			
			// Store the result so we can check if the account exists in the database.
			$stmt->store_result();
		}

		if($stmt->num_rows == 1) {
			$stmt->bind_result($public_id);
			$stmt->fetch();
			$stmt->close();
		}

		return $public_id;
	}





	/**
	 * Convert customer ID to public ID
	 * 
	 * @param    Int      $id          Customer ID
	 * @return   String   $public_id   Public Customer ID
	 */
	private function publicId2Id($public_id)
	{
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('SELECT id FROM company WHERE public_id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('s', $public_id);
			$stmt->execute();
			
			// Store the result so we can check if the account exists in the database.
			$stmt->store_result();
		}

		if($stmt->num_rows == 1) {
			$stmt->bind_result($id);
			$stmt->fetch();
			$stmt->close();
		}

		return $id;
	}





	/**
	 * Check if user is admin
	 * 
	 * @param    Int       $user_id     User ID
	 * @return   Boolean                True is admin, false is not
	 */
	private function userIsAdmin($user_id)
	{
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('SELECT admin FROM users WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $user_id);
			$stmt->execute();
			
			// Store the result so we can check if the account exists in the database.
			$stmt->store_result();
		}

		if($stmt->num_rows == 1) {
			$stmt->bind_result($admin);
			$stmt->fetch();
			$stmt->close();
		}

		if ($admin == 1) {
			return true;
		} else {
			return false;
		}
	}





	/**
	 * Check if logged in user is admin
	 * 
	 * @return   Boolean                True is admin, false is not
	 */
	public function isAdmin()
	{
		$db = Db::getInstance();
		$user_id = $this->thisUser['id'];

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('SELECT admin FROM users WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $user_id);
			$stmt->execute();
			
			// Store the result so we can check if the account exists in the database.
			$stmt->store_result();
		}

		if($stmt->num_rows == 1) {
			$stmt->bind_result($admin);
			$stmt->fetch();
			$stmt->close();
		}

		if ($admin == 1) {
			return true;
		} else {
			return false;
		}
	}


}