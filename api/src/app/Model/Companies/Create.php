<?php
namespace Kapps\Model\Companies;

use Kapps\Model\Database\Db;
use \Kapps\Model\Companies\Access;


/**
 * summary
 */
class Create
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



	public function create($p)
	{
		// Check for admin access to create company
		if (!$this->Access->isAdmin()) {
			return array('status' => 'error', 'message' => 'Access denied');
		}



		$public_id = $this->uniqueKey(8);

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
		if ($stmt = $this->db->prepare('INSERT INTO company SET public_id=?, domain=?, title=?, county=?, type_id=?, org_numb=?, website=?, type=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('ssssiiss', $public_id, $domain, $title, $county, $type_id, $org_numb, $website, $type);
			//$stmt->execute();

			if (!$stmt) {
				return array('status' => 'error', 'message' => $this->db->error);
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




	private function uniqueKey($limit = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randstring = '';
		for ($i = 0; $i < $limit; $i++) {
			$randstring .= $characters[rand(0, strlen($characters))];
		}
		return $randstring;
	}

}