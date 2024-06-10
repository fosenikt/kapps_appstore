<?php
namespace Kapps\Model\Municipality;

use Kapps\Model\Database\Db;


/**
 * summary
 */
class Get
{
	private $db;

	public function __construct()
	{
		$this->db = Db::getInstance();
	}



	public function get_municipalities()
	{
		$r = null;
		$type = 'Kommune';

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('SELECT * FROM company WHERE type=? ORDER BY title ASC')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('s', $type);
			$stmt->execute();
		}


		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$r[] = array(
				'public_id' => $row['public_id'],
				'title' => $row['title'],
				'county' => $row['county'],
				'type_id' => $row['type_id'],
				'org_numb' => $row['org_numb'],
				'website' => $row['website'],
				'domain' => $row['domain'],
				'type' => $row['type'],
				'logo' => $row['logo'],
			);
		}

		return $r;
	}

}