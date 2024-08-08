<?php
namespace Kapps\Model\Apps;

use Kapps\Model\Database\Db;
use Kapps\Model\Database\QueryBuilder;
use \Kapps\Model\Apps\Images;
use \Kapps\Model\Apps\Files;
use \Kapps\Model\Companies\Get as CompaniesGet;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Get
{
	private $db;
	private $Images;
	private $Companies;
	private $Files;
	private $thisUser;
	private $image_base_url;
	private $image_base_path;
	private $image_none;
	private $isAuthenticated;

	public function __construct()
	{
		$this->db = Db::getInstance();

		$this->Images = new Images();
		$this->Companies = new CompaniesGet();
		$this->Files = new Files();

		$this->thisUser = (new AuthUser())->me();

		if (!is_array($this->thisUser) || empty($this->thisUser['customer']['public_id'])) {
			$this->isAuthenticated = false;
		} else {
			$this->isAuthenticated = true;
		}

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
		$queryBuilder = new QueryBuilder();
		$queryBuilder->table('apps AS A')
			->select('A.*', 'T.title AS type_title', 'T.fa_icon AS type_icon', 'UC.firstname AS uc_firstname', 'UC.lastname AS uc_lastname', 'UC.mail AS uc_mail', 'UE.firstname AS ue_firstname', 'UE.lastname AS ue_lastname', 'UE.mail AS ue_mail', 'L.title AS license_title', 'L.link AS license_link')
			->join('app_types AS T', 'A.type_id', '=', 'T.id')
			->join('users AS UC', 'A.created_by', '=', 'UC.id')
			->join('users AS UE', 'A.updated_by', '=', 'UE.id')
			->join('licenses AS L', 'A.license_id', '=', 'L.id')
			->where('A.id', '=', $id);

		if ($this->isAuthenticated) {
			$queryBuilder->whereRaw('(A.status LIKE ? OR A.company_id = ?)', ['published', $this->thisUser['customer']['public_id']]);
		} else {
			$queryBuilder->where('A.status', 'LIKE', 'published');
		}
		
		$result = $queryBuilder->get();

		if (!empty($result)) {
			$r = $this->output($result[0]);
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

		$queryBuilder = new QueryBuilder();
		$queryBuilder->table('apps AS A')
			->select('A.*', 'T.title AS type_title', 'T.fa_icon AS type_icon', 'UC.firstname AS uc_firstname', 'UC.lastname AS uc_lastname', 'UC.mail AS uc_mail', 'UE.firstname AS ue_firstname', 'UE.lastname AS ue_lastname', 'UE.mail AS ue_mail', 'L.title AS license_title', 'L.link AS license_link')
			->join('app_types AS T', 'A.type_id', '=', 'T.id')
			->join('users AS UC', 'A.created_by', '=', 'UC.id')
			->join('users AS UE', 'A.updated_by', '=', 'UE.id')
			->join('licenses AS L', 'A.license_id', '=', 'L.id');

		if (isset($p['type']) && !empty($p['type'])) {
			$queryBuilder->where('A.type_id', '=', $p['type']);
		}

		if (isset($p['company']) && !empty($p['company'])) {
			$queryBuilder->where('A.company_id', '=', $p['company']);
		}

		// Only include published apps
		$queryBuilder->where('A.status', 'LIKE', 'published');

		// Get the results
		$results = $queryBuilder->get();

		if (!empty($results)) {
			foreach ($results as $row) {
				$r[] = $this->output($row, false);
			}
		}

		return $r;
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
	public function get_company_app($id)
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
		$result = $this->db->query($query);

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
	public function get_company_apps()
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
		$result = $this->db->query($query);

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
	public function get_company_published_apps($id)
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
		$result = $this->db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r[] = $this->output($row);
			}
		}

		return $r;
	}







	/**
	* Output all
	* Compose output array for application
	*
	* Security: Private
	*
	* @author Robert Andresen <ra@fosenikt.no>
	*
	* @param  	Array   $data      Data array from SQL-query
	* @return  	Array   $r         Array with app-data (see output)
	*/
	private function output($data, $full_output=true)
	{

		$isAuthenticated = (new AuthUser())->isAuthenticated();

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

		$tags = !empty($data['tags']) ? explode(',', $data['tags']) : null;
		$license_title = $data['license_title'] ?? null;
		$license_link = $data['license_link'] ?? null;


		if (!$isAuthenticated || !$full_output) {
			return array(
				'id' => $data['id'],
				'title' => $data['title'],
				'short_description' => $data['short_description'],
				'primary_image' => $primary_image,
				'images' => array($primary_image),
				'type' => array(
					'id' => $data['type_id'],
					'title' => $data['type_title'],
					'icon' => $data['type_icon'],
				),
				'tags' => array(
					'array' => $tags,
					'string' => $data['tags'],
				),
				'status' => $data['status'],
				'company' => $this->Companies->get_company_simple($data['company_id']),
				'license' => array(
					'id' => $data['license_id'],
					'title' => $license_title,
					'link' => $license_link
				),
			);
		}

		else {
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
					'title' => $license_title,
					'link' => $license_link
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


}