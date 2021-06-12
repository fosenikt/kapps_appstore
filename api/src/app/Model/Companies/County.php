<?php
namespace Kapps\Model\Companies;

use \Kapps\Model\General\Db;


/**
 * summary
 */
class County extends Db
{

	/**
	 * summary
	 */
	public function __construct() {}



	public function get_counties()
	{
		$r = null;
		$db = Db::getInstance();
		$type = 'Fylke';

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('SELECT * FROM company WHERE type=? ORDER BY title ASC')) {
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