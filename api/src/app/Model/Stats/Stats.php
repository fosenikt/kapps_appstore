<?php
namespace Kapps\Model\Stats;

use \Kapps\Model\General\Db;
use \Kapps\Model\Apps\Utils;

/**
 * summary
 */
class Stats extends Db
{

	public function __construct()
	{
		$this->Utils = new Utils;
	}



	public function num_published()
	{
		$query = "SELECT id FROM apps WHERE status LIKE 'published'";
		$db = Db::getInstance();
		$result = $db->query($query);

		return $result->num_rows;
	}


	public function last_published()
	{
		$r = array();

		$query = "SELECT * FROM apps WHERE status LIKE 'published' ORDER BY time_created DESC LIMIT 5";
		$db = Db::getInstance();
		$result = $db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r[] = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'short_description' => $row['short_description'],
					'primary_image' => $this->Utils->get_app_image($row['primary_image'], $row['id']),
					'company' => array(
						'public_id' => $row['company_id'],
						'name' => $row['company_title'],
						'logo' => $this->Utils->get_company_logo($row['company_logo']),
					)
				);
			}
		}

		return $r;
	}

}