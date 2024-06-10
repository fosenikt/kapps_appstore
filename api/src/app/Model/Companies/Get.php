<?php
namespace Kapps\Model\Companies;

use Kapps\Model\Database\Db;
use \Kapps\Model\Utils\AlphaID;


/**
 * summary
 */
class Get
{
	private $db;
	private $AlphaID;

	/**
	 * summary
	 */
	public function __construct()
	{
		$this->db = Db::getInstance();
		$this->AlphaID = new AlphaID;
	}




	/**
	 * Get company by Public ID
	 * 
	 * Security: None (backend only). Not available from controller or API now.
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	Int      $id    Company Public ID
	 * @return 	Array    $r     Array with company-data
	 */
	public function get_company($id)
	{
		$r = null;

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('SELECT * FROM company WHERE public_id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('s', $id);
			$stmt->execute();
		}


		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$r = array(
				'public_id' => $row['public_id'],
				'title' => $row['title'],
				'county' => $row['county'],
				'type_id' => $row['type_id'],
				'org_numb' => $row['org_numb'],
				'website' => $row['website'],
				'domain' => $row['domain'],
				'type' => $row['type'],
				'logo' => $this->get_logo($row['logo']),
			);
		}

		return $r;
	}




	/**
	 * Get all companies
	 * 
	 * Security: None (backend only)
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @return 	Array    $r     Array with company-data
	 */
	public function get_companies()
	{
		$r = null;

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('SELECT * FROM company ORDER BY title ASC')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			//$stmt->bind_param('s', $mail);
			$stmt->execute();
		}


		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {			
			if (empty($row['public_id'])) {
				$public_id = $this->uniqueKey(8);
				$this->store_public_id($row['id'], $public_id);
			} else {
				$public_id = $row['public_id'];
			}

			$r[] = array(
				'public_id' => $public_id,
				'title' => $row['title'],
				'county' => $row['county'],
				'type_id' => $row['type_id'],
				'org_numb' => $row['org_numb'],
				'website' => $row['website'],
				'domain' => $row['domain'],
				'type' => $row['type'],
				'logo' => $this->get_logo($row['logo']),
			);
		}

		return $r;
	}





	/**
	 * Get all companies (simple list)
	 * Used for search in e.g. new/edit user
	 * Security: None (backend only)
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @return 	Array    $r     Array with company-data
	 */
	public function get_companies_simple()
	{
		$r = null;

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('SELECT id, title FROM company ORDER BY title ASC')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			//$stmt->bind_param('s', $mail);
			$stmt->execute();
		}


		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$r[] = array(
				'id' => $row['id'],
				'title' => $row['title'],
			);
		}

		return $r;
	}





	/**
	 * Get company by ID (Not public ID)
	 * Ment for internal queries where ID is used, insted of public ID.
	 * Like users-table
	 * 
	 * Security: None (backend only). Not available from controller or API now.
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	Int      $id    Company ID
	 * @return 	Array    $r     Array with company-data
	 */
	public function get_company_by_id($id)
	{
		$r = null;

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('SELECT * FROM company WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $id);
			$stmt->execute();
		}


		$result = $stmt->get_result();
		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$r = array(
					'public_id' => $row['public_id'],
					'title' => $row['title'],
					'county' => $row['county'],
					'type_id' => $row['type_id'],
					'org_numb' => $row['org_numb'],
					'website' => $row['website'],
					'domain' => $row['domain'],
					'type' => $row['type'],
					'logo' => $this->get_logo($row['logo']),
				);
			}
		}

		return $r;
	}




	public function get_counties()
	{
		$r = null;
		$type = 'Fylke';

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('SELECT * FROM company WHERE type=? ORDER BY title ASC')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('s', $type);
			$stmt->execute();
		}


		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$r[] = array(
				'public_id' => $row['public_id'],
				'title' => $row['title'],
				'county' => $row['county'],
				'type_id' => $row['type_id'],
				'org_numb' => $row['org_numb'],
				'website' => $row['website'],
				'domain' => $row['domain'],
				'type' => $row['type'],
				'logo' => $row['logo'],
			);
		}

		return $r;
	}




	/**
	 * Get company logo
	 * 
	 * Security: None
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	String   $filename    Filename for logo
	 * @return 	Array    $r           Array with image and thumbnail
	 */
	public function get_logo($filename)
	{
		if (empty($filename)) {
			return array(
				'image' => '/assets/images/icons/building.svg',
				'thumb' => '/assets/images/icons/building.svg'
			);
		}

		$exp = explode('.', $filename);
		$ext = strtolower(end($exp));

		if (empty($filename)) {
			$logo = array(
				'image' => '/assets/images/icons/building.svg',
				'thumb' => '/assets/images/icons/building.svg'
			);
		} else {
			if ($ext != 'svg' && $ext != 'webp') {
				$logo = array(
					'image' => '//'.URL.'/data/companies_logo/'.$filename,
					'thumb' => '//'.URL.'/data/companies_logo/_thumbs/'.$filename
				);
			} else {
				$logo = array(
					'image' => '//'.URL.'/data/companies_logo/'.$filename,
					'thumb' => '//'.URL.'/data/companies_logo/'.$filename
				);
			}
		}

		return $logo;
	}





	private function store_public_id($company_id, $public_id)
	{
		$r = null;


		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('UPDATE company SET public_id=? WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('si', $public_id, $company_id);
			//$stmt->execute();

			if (!$stmt) {
				return array('status' => 'error', 'message' => $this->db->error);
			}

			if (!$stmt->execute()) {
				return array('status' => 'error', 'message' => $stmt->error);
			}
		}

		if($stmt->affected_rows == 1) {
			$status = array('status' => 'status');
		} else {
			$status = array('status' => 'error', 'message' => 'Error creating user', 'msgid' => 2);
		}

		$stmt->close();
		return $status;
	}



	private function uniqueKey($limit = 10) {

		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$randstring = '';

		for ($i = 0; $i < $limit; $i++) {

			$randstring .= $characters[rand(0, strlen($characters))];
		}

		return $randstring;
	}
}