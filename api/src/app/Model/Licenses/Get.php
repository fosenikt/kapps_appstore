<?php
namespace Kapps\Model\Licenses;

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


	public function get_licenses()
	{
		$r = array(); // Declare return-array


		// Query apps
		$query = "SELECT * FROM licenses";
		$result = $this->db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r[] = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'description' => $row['description'],
					'link' => $row['link'],
					'details' => $this->get_details($row['id']),
				);
			}
		}

		return $r;
	}



	public function get_license($id)
	{
		$r = array(); // Declare return-array


		// Query apps
		$query = "SELECT * FROM licenses WHERE id='$id'";
		$result = $this->db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r[] = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'description' => $row['description'],
					'link' => $row['link'],
					'details' => $this->get_details($row['id']),
				);
			}
		}

		return $r;
	}



	public function get_details($license_id)
	{
		$r = null; // Declare return-array

		// Query apps
		$query = "SELECT D.*
				  FROM license_has_details AS HAS
				  INNER JOIN license_details AS D ON HAS.details_id = D.id
				  WHERE HAS.license_id='$license_id'";
		$result = $this->db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r[$row['type']][] = array(
					'id' => $row['id'],
					'type' => $row['type'],
					'title' => $row['title'],
					'description' => $row['description'],
				);
			}
		}

		return $r;
	}

}