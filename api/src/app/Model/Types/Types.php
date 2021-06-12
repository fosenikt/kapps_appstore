<?php
namespace Kapps\Model\Types;

use \Kapps\Model\General\Db;

/**
 * summary
 */
class Types extends Db
{

	public function __construct()
	{
	}


	public function get_types()
	{
		$r = array();

		$query = "SELECT * FROM app_types";
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
			'fa_icon' => $data['fa_icon'],
		);
	}

}