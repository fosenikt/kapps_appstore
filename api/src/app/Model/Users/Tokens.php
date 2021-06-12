<?php
namespace Kapps\Model\Users;

use \Kapps\Model\General\Db;
use \Kapps\Model\Auth\Utils;
use \Kapps\Model\Auth\User as AuthUser;


/**
 * summary
 */
class Tokens extends Db
{

	public function __construct()
	{
		$this->Utils = new Utils();

		$this->AuthUser = new AuthUser;
		$this->me = $this->AuthUser->me();
	}




	/**
	 * Create a token
	 * 
	 * Security: Checks if user is admin
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	Int      $id    App ID
	 * @return 	Array    $r     Array with app-data (see output)
	 */
	public function create_token($user_id, $title)
	{

		if ($this->me['admin'] != 1) {
			return array('status' => 'error', 'message' => 'No access');
		}

		if (!isset($user_id) || empty($user_id)) {
			return array('status' => 'error', 'message' => 'User ID missing');
		}

		if (!isset($title) || empty($title)) {
			return array('status' => 'error', 'message' => 'Title missing');
		}


		$payload = array(
			'user_id' => $user_id
		);
		$expires = 31536000 * 42; // 365 days * 42 years
		$token = $this->Utils->create_token($payload, $expires, $title);

		return $token;
	}






	/**
	 * Get user tokens
	 * 
	 * Security: Checks if user is admin
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	Int      $user_id   User ID
	 * @return 	Array    $r         Array with session data
	 */
	public function get_tokens($user_id)
	{
		if ($this->me['admin'] != 1) {
			return array('status' => 'error', 'message' => 'No access');
		}

		if (!isset($user_id) || empty($user_id)) {
			return array('status' => 'error', 'message' => 'User ID missing');
		}


		$r = null;
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('SELECT * FROM user_sessions WHERE user_id=? ORDER BY time_last_active DESC, time_created DESC')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $user_id);
			$stmt->execute();
		}


		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$r[] = array(
				'id' => $row['id'],
				'title' => $row['title'],
				'time_created' => $row['time_created'],
				'time_expires' => $row['time_expires'],
				'time_last_active' => $row['time_last_active'],
				'user_agent' => $row['user_agent'],
				'ip_address' => $row['ip_address'],
				'status' => $row['status'],
			);
		}

		return $r;
	}






	




	/**
	 * Delete token/session
	 * 
	 * Security: Checks if user is admin
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	Int      $id    Token ID
	 * @return 	Array    $r     Status-array
	 */
	public function delete_token($id)
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
		if ($stmt = $db->prepare('DELETE FROM user_sessions WHERE id=?')) {
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
			$status = array('status' => 'error', 'message' => 'Error deleting token', 'msgid' => 2);
		}

		$stmt->close();
		return $status;
	}

}