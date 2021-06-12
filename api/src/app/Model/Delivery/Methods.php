<?php
namespace Kapps\Model\Delivery;

use \Kapps\Model\General\Db;

/**
 * summary
 */
class Methods extends Db
{

	public function __construct()
	{
	}


	public function get_methods()
	{
		$r = array();

		$query = "SELECT * FROM apps_delivery_methods";
		$db = Db::getInstance();
		$result = $db->query($query);

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