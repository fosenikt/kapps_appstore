<?php
namespace Kapps\Model\Stats;

use \Kapps\Model\General\Db;
use \Kapps\Model\Auth\User as AuthUser;
use \Kapps\Model\Apps\Utils;

/**
 * summary
 */
class Log extends Db
{
	private $Utils;
	private $AuthUser;
	private $me;

	public function __construct()
	{
		$this->Utils = new Utils;

		$this->AuthUser = new AuthUser;
		$this->me = $this->AuthUser->me();
	}



	public function log($type, $entity_id, $entity_id2=null)
	{
		$user_id = $this->me['id'];
		$date = date('Y-m-d');

		// Create DB instance
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('REPLACE INTO stats SET date_created=?, type=?, user_id=?, entity_id=?, entity_id2=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('ssiss', $date, $type, $user_id, $entity_id, $entity_id2);
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
			$status = array('status' => 'error', 'message' => 'Error updating company', 'msgid' => 2);
		}

		$stmt->close();
		return $status;
	}


}