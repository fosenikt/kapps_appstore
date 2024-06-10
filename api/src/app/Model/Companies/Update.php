<?php
namespace Kapps\Model\Companies;

use Kapps\Model\Database\Db;
use \Kapps\Model\Companies\Access;


/**
 * summary
 */
class Update
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



	public function update($p)
	{
		
		if (isset($p['public_id']) && !empty($p['public_id'])) {
			$public_id = $p['public_id'];
		} else {
			return array('status' => 'error', 'message' => 'No ID provided');
		}

		// Check for admin access to update company
		if (!$this->Access->chk_customer_access($public_id)) {
			return array('status' => 'error', 'message' => 'Access denied');
		}





		if (isset($p['domain']) && !empty($p['domain'])) {
			$domain = $p['domain'];
		} else {
			$domain = '';
		}

		if (isset($p['title']) && !empty($p['title'])) {
			$title = $p['title'];
		} else {
			$title = '';
		}

		if (isset($p['county']) && !empty($p['county'])) {
			$county = $p['county'];
		} else {
			$county = '';
		}

		if (isset($p['type_id']) && !empty($p['type_id'])) {
			$type_id = $p['type_id'];
		} else {
			$type_id = '';
		}

		if (isset($p['org_numb']) && !empty($p['org_numb'])) {
			$org_numb = $p['org_numb'];
		} else {
			$org_numb = '';
		}

		if (isset($p['website']) && !empty($p['website'])) {
			$website = $p['website'];
		} else {
			$website = '';
		}

		if (isset($p['type']) && !empty($p['type'])) {
			$type = $p['type'];
		} else {
			$type = '';
		}

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('UPDATE company SET domain=?, title=?, county=?, type_id=?, org_numb=?, website=?, type=? WHERE public_id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('sssiisss', $domain, $title, $county, $type_id, $org_numb, $website, $type, $public_id);

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
			$status = array('status' => 'success', 'message' => 'No affected rows. Maybe nothing is changed?', 'msgid' => 2);
		}

		$stmt->close();
		return $status;
	}

}