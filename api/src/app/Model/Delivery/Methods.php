<?php
namespace Kapps\Model\Delivery;

use Kapps\Model\Database\Db;

/**
 * summary
 */
class Methods
{
	private $db;

	public function __construct()
	{
		$this->db = Db::getInstance();
	}


	public function get_methods()
	{
		$r = array();

		$query = "SELECT * FROM apps_delivery_methods";
		$result = $this->db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r[] = $this->output($row);
			}
		}

		return $r;
	}



	private function output($data)
	{
		return array(
			'id' => $data['id'],
			'title' => $data['title'],
		);
	}

}