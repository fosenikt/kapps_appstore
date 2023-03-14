<?php
namespace Kapps\Model\Apps;

use \Kapps\Model\General\Db;
use \Kapps\Model\Apps\Images;
use \Kapps\Model\Apps\Files;
use \Kapps\Model\Companies\Companies;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Apps extends Db
{
	private $Images;
	private $Companies;
	private $Files;
	private $AuthUser;
	private $thisUser;
	private $image_base_url;
	private $image_base_path;
	private $image_none;

	public function __construct()
	{
		$this->Images = new Images();
		$this->Companies = new Companies();
		$this->Files = new Files();

		$this->AuthUser = new AuthUser;
		$this->thisUser = $this->AuthUser->me();

		$this->image_base_url = '//'.URL.'/data/apps/';
		$this->image_base_path = '/var/www/html/data/apps/';
		$this->image_none = '//'.FRONTEND_HOST.'/assets/images/icons/rpa_default.png';
	}






	/**
	* Get app
	*
	* Security: Allow view if published or logged in user has same company as app
	*
	* @author Robert Andresen <ra@fosenikt.no>
	*
	* @param  	Int     $id     App ID
	* @return  	Array   $r      Array with app-data (see output)
	*/
	public function get_app($id)
	{
		$r = array(); // Declare return-array

		// Query apps
		$query = "SELECT A.*,
						 T.title AS type_title, T.fa_icon AS type_icon,
						 UC.firstname AS uc_firstname, UC.lastname AS uc_lastname, UC.mail AS uc_mail,
						 UE.firstname AS ue_firstname, UE.lastname AS ue_lastname, UE.mail AS ue_mail,
						 L.title AS license_title, L.link AS license_link
				  FROM apps AS A
				  INNER JOIN app_types AS T ON A.type_id = T.id
				  INNER JOIN users AS UC ON A.created_by = UC.id
				  INNER JOIN users AS UE ON A.updated_by = UE.id
				  INNER JOIN licenses AS L ON A.license_id = L.id
				  WHERE A.id='$id' AND (A.status LIKE 'published' OR A.company_id='{$this->thisUser['customer']['public_id']}')";
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
	* Get apps
	* Allow query for type and company to filter apps
	*
	* Security: Only published apps
	*
	* @author Robert Andresen <ra@fosenikt.no>
	*
	* @param  	Array   $p      Filter parameters
	* @return  	Array   $r      Array with app-data (see output)
	*/
	public function get_apps($p = array())
	{
		$r = array(); // Declare return-array
		$qWhereArr = array(); // Declare return-array


		// Build query
		if (isset($p['type']) && !empty($p['type']))
			$qWhereArr[] = $this->create_query('A.type_id', $p['type']);
		
		if (isset($p['company']) && !empty($p['company']))
			$qWhereArr[] = $this->create_query('A.company_id', $p['company']);

		if (is_array($qWhereArr) && count($qWhereArr) > 0) {
			$qWhere = "WHERE (".join(' AND ', $qWhereArr).") AND A.status LIKE 'published'";
		} else {
			$qWhere = "WHERE A.status LIKE 'published'";
		}


		// Query apps
		$query = "SELECT A.*,
						 T.title AS type_title, T.fa_icon AS type_icon,
						 UC.firstname AS uc_firstname, UC.lastname AS uc_lastname, UC.mail AS uc_mail,
						 UE.firstname AS ue_firstname, UE.lastname AS ue_lastname, UE.mail AS ue_mail,
						 L.title AS license_title, L.link AS license_link
				  FROM apps AS A
				  INNER JOIN app_types AS T ON A.type_id = T.id
				  INNER JOIN users AS UC ON A.created_by = UC.id
				  INNER JOIN users AS UE ON A.updated_by = UE.id
				  INNER JOIN licenses AS L ON A.license_id = L.id
				  $qWhere";
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
	 * Build mysql-query-string for single parameter
	 * from comma-seperated list.
	 * 
	 * Security: Private
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	String    $column   Column name
	 * @param 	String    $input    Comma-seperated list (e.g. 1,2,3)
	 * @return 	String    $qWhere   Query string. E.g. (type='1' OR type='2' OR type='3')
	 */
	private function create_query($column, $input)
	{
		if (empty($input)) return '';

		if (isset($input)) {
			$exploded = explode(',', $input);
			foreach ($exploded as $key => $item) {
				$qWhereArr[] = "$column='$item'";
			}

			if (is_array($qWhereArr) && count($qWhereArr) > 0) {
				$qWhere = '('.join(' OR ', $qWhereArr).')';
			}

			return $qWhere;
		}

		return '';
	}






	/**
	* Output
	* Compose output array for application
	*
	* Security: Private
	*
	* @author Robert Andresen <ra@fosenikt.no>
	*
	* @param  	Array   $data      Data array from SQL-query
	* @return  	Array   $r         Array with app-data (see output)
	*/
	private function output($data)
	{

		$edit_access = 0;
		if ($this->thisUser['customer']['public_id'] == $data['company_id']) {
			$edit_access = 1;
		}
		elseif ($this->thisUser['id'] == $data['created_by']) {
			$edit_access = 1;
		}


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
			'license' => array(
				'id' => $data['license_id'],
				'title' => $data['license_title'],
				'link' => $data['license_link']
			),
			'tags' => array(
				'array' => $tags,
				'string' => $data['tags'],
			),
			'link_source_code' => $data['link_source_code'],
			'status' => $data['status'],
			'files' => $this->Files->get_app_files($data['id']),
			'edit_access' => $edit_access,
		);
	}


}