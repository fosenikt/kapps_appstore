<?php
namespace Kapps\Model\General;

use \Kapps\Model\General\Db;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Event extends db
{

	public function __construct()
	{
		$this->AuthUser = new AuthUser;
		$this->thisUser = $this->AuthUser->me();
	}


	public function add($type, $entity_id, $entity_id2=null)
	{
		$user_id = $this->thisUser['id'];

		// Create DB instance
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('INSERT INTO stats SET type=?, user_id=?, entity_id=?, entity_id2=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('siss', $type, $user_id, $entity_id, $entity_id2);
			//$stmt->execute();

			if (!$stmt) {
				return array('status' => 'error', 'message' => $db->error);
			}

			if (!$stmt->execute()) {
				return array('status' => 'error', 'message' => $stmt->error);
			}
		}

		if($stmt->affected_rows == 1) {
			$status = array('status' => 'success', 'public_id' => $public_id);
		} else {
			$status = array('status' => 'error', 'message' => 'Error updating company', 'msgid' => 2);
		}

		$stmt->close();
		return $status;
	}

}