<?php
namespace Kapps\Model\Stats;

use Kapps\Model\Database\Db;
use \Kapps\Model\Auth\User as AuthUser;
use \Kapps\Model\Apps\Utils;

/**
 * summary
 */
class Log
{
	private $db;
	private $Utils;
	private $thisUser;
	private $me;

	public function __construct()
	{
		$this->Utils = new Utils;

		$this->db = Db::getInstance();
		$this->thisUser = (new AuthUser())->me();
	}



	public function log($type, $entity_id, $entity_id2=null)
	{
		$date = date('Y-m-d');

		$get_user = (new AuthUser())->me();

		if (is_array($get_user) && isset($get_user['id'])) {
			$user_id = $get_user['id'];
		} else {
			$user_id = 0;
		}

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('REPLACE INTO stats SET date_created=?, type=?, user_id=?, entity_id=?, entity_id2=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('ssiss', $date, $type, $user_id, $entity_id, $entity_id2);
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
			$status = array('status' => 'error', 'message' => 'Error updating company', 'msgid' => 2);
		}

		$stmt->close();
		return $status;
	}


}