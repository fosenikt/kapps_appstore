<?php
namespace Kapps\Model\Users;

use Kapps\Model\Database\Db;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Delete
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


		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('DELETE FROM users WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $id);
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
			$status = array('status' => 'error', 'message' => 'Error deleting user', 'msgid' => 2);
		}

		$stmt->close();
		return $status;
	}


}