<?php
namespace Kapps\Model\Stats;

use Kapps\Model\Database\Db;
use \Kapps\Model\Apps\Get as AppsGet;
use \Kapps\Model\Apps\Utils;

/**
 * summary
 */
class Stats
{
	private $db;
	private $Apps;
	private $Utils;

	public function __construct()
	{
		$this->db = Db::getInstance();

		$this->Apps = new AppsGet;
		$this->Utils = new Utils;
	}



	public function num_published()
	{
		$query = "SELECT id FROM apps WHERE status LIKE 'published'";
		$result = $this->db->query($query);

		return $result->num_rows;
	}


	public function last_published()
	{
		$r = null;

		$query = "SELECT A.id, A.time_created, A.title, A.short_description, A.primary_image, A.company_id, C.title AS company_title, C.logo AS company_logo
				  FROM apps AS A 
				  INNER JOIN company AS C ON A.company_id = C.public_id
				  WHERE A.status LIKE 'published' 
				  ORDER BY A.time_created DESC 
				  LIMIT 5";
		$result = $this->db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r[] = array(
					'id' => $row['id'],
					'time_created' => $row['time_created'],
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




	public function most_popular_apps()
	{
		$r = null;
		$stats = null;

		$query = "SELECT entity_id, COUNT(*)
				  FROM stats
				  WHERE type LIKE 'app' 
				  	AND date_created BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
				  GROUP BY entity_id
				  ORDER BY COUNT(*) DESC
				  LIMIT 5";
		$result = $this->db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$stats[$row['entity_id']] = $row['COUNT(*)'];
			}
		}

		if (is_array($stats) && count($stats)) {
			foreach ($stats as $app_id => $num_stats) {
				$r[] = $this->get_app($app_id);
			}
		}



		return $r;
	}





	private function get_app($id)
	{
		$r = null;

		$query = "SELECT A.id, A.time_created, A.title, A.short_description, A.primary_image, A.company_id, C.title AS company_title, C.logo AS company_logo
				  FROM apps AS A 
				  INNER JOIN company AS C ON A.company_id = C.public_id
				  WHERE A.status LIKE 'published' AND A.id='$id'
				  ORDER BY A.time_created DESC 
				  LIMIT 5";
		$result = $this->db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r = array(
					'id' => $row['id'],
					'time_created' => $row['time_created'],
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