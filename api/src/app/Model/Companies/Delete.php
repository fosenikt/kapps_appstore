<?php
namespace Kapps\Model\Companies;

use Kapps\Model\Database\Db;
use \Kapps\Model\Companies\Access;


/**
 * summary
 */
class Delete
{
	private $db;
	private $Access;

	/**
	 * summary
	 */
	public function __construct()
	{
		$this->db = Db::getInstance();
		$this->Access = new Access();
	}



	public function delete($public_id)
	{
		// Check for admin access to delete company
		if (!$this->Access->isAdmin()) {
			return array('status' => 'error', 'message' => 'Access denied');
		}

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('DELETE FROM company WHERE public_id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('s', $public_id);
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