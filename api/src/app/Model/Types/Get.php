<?php
namespace Kapps\Model\Types;

use Kapps\Model\Database\Db;

/**
 * Get application types (aka. categories)
 * 
 * Default:
 * [
 *  {"id": "1", "title": "Applikasjon", "fa_icon": "fal fa-box-full"},
 *   {"id": "2", "title": "Integrasjon", "fa_icon": "fal fa-cogs"},
 *   {"id": "3", "title": "RPA", "fa_icon": "fal fa-user-robot"},
 *   {"id": "4", "title": "Dokument", "fa_icon": "fal fa-books"},
 *   {"id": "5", "title": "Skript", "fa_icon": "fal fa-code"}
 * ]
 */
class Get
{
	private $db;

	public function __construct()
	{
		$this->db = Db::getInstance();
	}


	public function get_types()
	{
		$r = array();

		$query = "SELECT * FROM app_types";
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
			'fa_icon' => $data['fa_icon'],
		);
	}

}