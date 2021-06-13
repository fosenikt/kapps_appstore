<?php
namespace Kapps\Model\Apps;

use \Kapps\Model\General\Db;
use \Kapps\Model\Auth\User as AuthUser;
use \Kapps\Model\Apps\Images;
use \Kapps\Model\Companies\Companies;

/**
 * summary
 */
class Company extends Db
{

	public function __construct()
	{
		$this->Images = new Images();
		$this->Companies = new Companies();

		$this->AuthUser = new AuthUser;
		$this->thisUser = $this->AuthUser->me();

		$this->image_base_url = '//'.URL.'/data/apps/';
		$this->image_base_path = '/var/www/html/data/apps/';
		$this->image_none = '//'.FRONTEND_HOST.'/assets/images/icons/rpa_default.png';
	}




	/**
	 * Get app for company
	 * 
	 * Security: Checks if logged in user har same company as app
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	Int      $id    App ID
	 * @return 	Array    $r     Array with app-data (see outout)
	 */
	public function get_app($id)
	{
		$r = array(); // Declare return-array
		
		if (!isset($this->thisUser['customer']) || empty($this->thisUser['customer'])) {
			return null;
		}

		// Query apps
		$query = "SELECT A.*,
						 T.title AS type_title, T.fa_icon AS type_icon,
						 UC.firstname AS uc_firstname, UC.lastname AS uc_lastname, UC.mail AS uc_mail,
						 UE.firstname AS ue_firstname, UE.lastname AS ue_lastname, UE.mail AS ue_mail
				  FROM apps AS A
				  INNER JOIN app_types AS T ON A.type_id = T.id
				  INNER JOIN users AS UC ON A.created_by = UC.id
				  INNER JOIN users AS UE ON A.updated_by = UE.id
				  WHERE A.id='$id' AND A.company_id='{$this->thisUser['customer']['public_id']}'";
		$db = Db::getInstance();
		$result = $db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r = $this->output($row);
			}
		}

		return $r;
	}






	/**
	 * Get apps for company (aka. my company apps)
	 * 
	 * Security: Checks if logged in user har same company as app
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @return 	Array    $r     Array with app-data (see outout)
	 */
	public function get_apps()
	{
		$r = array(); // Declare return-array

		if (!isset($this->thisUser['customer']) || empty($this->thisUser['customer'])) {
			return null;
		}

		// Query apps
		$query = "SELECT A.*,
						 T.title AS type_title, T.fa_icon AS type_icon,
						 UC.firstname AS uc_firstname, UC.lastname AS uc_lastname, UC.mail AS uc_mail,
						 UE.firstname AS ue_firstname, UE.lastname AS ue_lastname, UE.mail AS ue_mail
				  FROM apps AS A
				  INNER JOIN app_types AS T ON A.type_id = T.id
				  INNER JOIN users AS UC ON A.created_by = UC.id
				  INNER JOIN users AS UE ON A.updated_by = UE.id
				  WHERE A.company_id='{$this->thisUser['customer']['public_id']}'
				  ORDER BY A.title ASC";
		$db = Db::getInstance();
		$result = $db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r[] = $this->output($row);
			}
		}

		return $r;
	}





	






	/**
	 * Get published (public) apps for company
	 * 
	 * Security: Checks if logged in user har same company as app
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @return 	Array    $r     Array with app-data (see outout)
	 */
	public function get_published_apps($id)
	{
		$r = array(); // Declare return-array

		// Query apps
		$query = "SELECT A.*,
						 T.title AS type_title, T.fa_icon AS type_icon,
						 UC.firstname AS uc_firstname, UC.lastname AS uc_lastname, UC.mail AS uc_mail,
						 UE.firstname AS ue_firstname, UE.lastname AS ue_lastname, UE.mail AS ue_mail
				  FROM apps AS A
				  INNER JOIN app_types AS T ON A.type_id = T.id
				  INNER JOIN users AS UC ON A.created_by = UC.id
				  INNER JOIN users AS UE ON A.updated_by = UE.id
				  WHERE A.company_id='$id' AND A.status LIKE 'published'
				  ORDER BY A.title ASC";
		$db = Db::getInstance();
		$result = $db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r[] = $this->output($row);
			}
		}

		return $r;
	}






	/**
	 * Output
	 * Compose output array for application
	 *
	 * Security: Private
	 *
	 * @author Robert Andresen <ra@fosenikt.no>
	 *
	 * @param  	  Array   $data      Data array from SQL-query
	 * @return    Array   $r         Array with app-data (see output)
	 */
	private function output($data)
	{

		$get_app_images = $this->Images->get_app_images($data['id']);

		if (empty($data['primary_image'])) {
			$primary_image = array(
				'image' => $this->image_none,
				'thumb' => $this->image_none,
			);
		} else {
			if (file_exists($this->image_base_path.$data['id'].'/'.$data['primary_image'])) {
				$primary_image = array(
					'image' => $this->image_base_url.$data['id'].'/'.$data['primary_image'],
					'thumb' => $this->image_base_url.$data['id'].'/_thumbs/'.$data['primary_image'],
				);
			} else {
				$primary_image = array(
					'image' => $this->image_none,
					'thumb' => $this->image_none,
				);
			}
		}

		if (!empty($data['tags'])) {
			$tags = explode(',', $data['tags']);
		} else {
			$tags = null;
		}

		return array(
			'id' => $data['id'],
			'time_created' => $data['time_created'],
			'time_edited' => $data['time_edited'],
			'created_by' => array(
				'id' => $data['created_by'],
				'firstname' => $data['uc_firstname'],
				'lastname' => $data['uc_lastname'],
				'mail' => $data['uc_mail'],
			),
			'updated_by' => array(
				'id' => $data['updated_by'],
				'firstname' => $data['ue_firstname'],
				'lastname' => $data['ue_lastname'],
				'mail' => $data['ue_mail'],
			),
			'company' => $this->Companies->get_company($data['company_id']),
			'title' => $data['title'],
			'short_description' => $data['short_description'],
			'description' => $data['description'],
			'installation' => $data['installation'],
			'primary_image' => $primary_image,
			'images' => $get_app_images,
			'type' => array(
				'id' => $data['type_id'],
				'title' => $data['type_title'],
				'icon' => $data['type_icon'],
			),
			'delivery_id' => $data['delivery_id'],
			'license_id' => $data['license_id'],
			'tags' => array(
				'array' => $tags,
				'string' => $data['tags'],
			),
			'link_source_code' => $data['link_source_code'],
			'status' => $data['status'],
		);
	}

}