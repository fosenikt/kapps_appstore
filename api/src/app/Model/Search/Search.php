<?php
namespace Kapps\Model\Search;

use Kapps\Model\Database\Db;
use Kapps\Model\Users\Utils AS UserUtils;

/**
 * summary
 */
class Search
{
	private $db;
	private $image_base_url;
	private $image_base_path;
	private $image_none;

	public function __construct()
	{
		$this->db = Db::getInstance();

		$this->image_base_url = '//'.URL.'/data/apps/';
		$this->image_base_path = '/var/www/html/data/apps/';
		$this->image_none = '//'.FRONTEND_HOST.'/assets/images/icons/rpa_default.png';
	}





	public function all($q, $limit=10)
	{
		$r = array(
			'apps' => null,
			'companies' => null
		);

		$r['apps'] = $this->apps($q, $limit);
		$r['companies'] = $this->companies($q, $limit);

		return $r;
	}





	public function apps($q, $limit=10)
	{

		$r = null;

		// Check for content in search-string, and split each words into an array
		if (strlen($q) > 1)
			$qarr = explode(" ", $q);

		$query = "SELECT A.id, A.title, A.short_description, A.primary_image, A.company_id, 
					C.title AS company_title, C.logo AS company_logo
				  FROM apps AS A
				  INNER JOIN company AS C ON C.public_id = A.company_id
				  INNER JOIN users AS U ON U.id = A.created_by
				  ";

		// Set counter to 0
		$t = 0;

		// Count words in search-string
		$n = count($qarr);
		
		// Loop each words and build the query to search for each word
		if (strlen($q) > 1) {
			$query .= " WHERE ";

			foreach($qarr as $so) {
			  $query .= "(
			  	        A.title LIKE '%$so%' OR 
			  	        A.short_description LIKE '%$so%' OR 
			  	        A.tags LIKE '%$so%' OR 
			  	        C.title LIKE '%$so%'
			  	       ) ";
			  
				
				// Add AND to earch word
				if($t++ < $n - 1)
				{
					$query .= "AND ";
				}
			}
		}
		
		// End search string
		$query .= " AND (A.status='published')";
		$query .= " ORDER BY A.title ASC";
		$query .= " LIMIT $limit";


		$result = $this->db->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			

			$r[] = array(
				'id' => $row['id'],
				'title' => $row['title'],
				'short_description' => $row['short_description'],
				'primary_image' => $this->get_app_image($row['primary_image'], $row['id']),
				'company' => array(
					'public_id' => $row['company_id'],
					'name' => $row['company_title'],
					'logo' => $this->get_company_logo($row['company_logo']),
				)
			);
		}

		return $r;
	}






	public function companies($q, $limit=10)
	{

		$r = null;

		// Check for content in search-string, and split each words into an array
		if (strlen($q) > 1)
			$qarr = explode(" ", $q);

		$query = "SELECT * FROM company AS C";

		// Set counter to 0
		$t = 0;

		// Count words in search-string
		$n = count($qarr);
		
		// Loop each words and build the query to search for each word
		if (strlen($q) > 1) {
			$query .= " WHERE ";

			foreach($qarr as $so) {
			  $query .= "(
			  	        C.domain LIKE '%$so%' OR 
			  	        C.title LIKE '%$so%' OR 
			  	        C.org_numb LIKE '%$so%'
			  	       ) ";
			  
				
				// Add AND to earch word
				if($t++ < $n - 1)
				{
					$query .= "AND ";
				}
			}
		}
		
		// End search string
		$query .= " ORDER BY C.title ASC";
		$query .= " LIMIT $limit";


		$result = $this->db->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			

			$r[] = array(
				'public_id' => $row['public_id'],
				'title' => $row['title'],
				'county' => $row['county'],
				'type_id' => $row['type_id'],
				'org_numb' => $row['org_numb'],
				'website' => $row['website'],
				'domain' => $row['domain'],
				'type' => $row['type'],
				'logo' => $this->get_company_logo($row['logo']),
			);
		}

		return $r;
	}





	private function get_app_image($filename, $app_id)
	{
		if (empty($filename)) {
			$primary_image = array(
				'image' => $this->image_none,
				'thumb' => $this->image_none,
			);
		} else {
			if (file_exists($this->image_base_path.$app_id.'/'.$filename)) {
				$primary_image = array(
					'image' => $this->image_base_url.$app_id.'/'.$filename,
					'thumb' => $this->image_base_url.$app_id.'/_thumbs/'.$filename,
				);
			} else {
				$primary_image = array(
					'image' => $this->image_none,
					'thumb' => $this->image_none,
				);
			}
		}

		return $primary_image;
	}





	private function get_company_logo($filename)
	{
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





	public function users($q, $limit = 20)
	{
		$r = null;

		// Check for content in the search string and split each word into an array
		if (strlen($q) > 1) {
			$qarr = explode(" ", $q);
		}

		// Base query with join
		$query = "
			SELECT U.*, C.public_id AS company_public_id, C.title AS company_title, C.logo AS company_logo
			FROM users AS U
			LEFT JOIN company AS C ON U.customer_id = C.id
		";

		// Initialize search conditions
		$conditions = [];
		$params = [];
		$param_types = '';

		// Build conditions if search string has content
		if (strlen($q) > 1) {
			foreach ($qarr as $so) {
				$conditions[] = "(U.firstname LIKE ? OR U.lastname LIKE ? OR U.mail LIKE ? OR U.mobile LIKE ? OR C.title LIKE ?)";
				$param_types .= 'sssss'; // Add five 's' for each condition
				$search_term = '%' . $so . '%';
				$params = array_merge($params, array_fill(0, 5, $search_term));
			}
		}

		// Append conditions to the query
		if (!empty($conditions)) {
			$query .= " WHERE " . implode(" AND ", $conditions);
		}

		// Add limit to the query
		$query .= " LIMIT ?";

		// Prepare statement
		$stmt = $this->db->prepare($query);
		$param_types .= 'i'; // 'i' for integer limit parameter
		$params[] = $limit;

		// Bind parameters dynamically
		$stmt->bind_param($param_types, ...$params);

		// Execute and fetch results
		$stmt->execute();
		$result = $stmt->get_result();

		while ($row = $result->fetch_assoc()) {
			$r[] = array(
				'id' => $row['id'],
				'firstname' => $row['firstname'],
				'lastname' => $row['lastname'],
				'initials' => $row['initials'],
				'displayname' => UserUtils::displayname($row['firstname'], $row['lastname'], $row['mail']),
				'mail' => $row['mail'],
				'mobile' => $row['mobile'],
				'status' => $row['status'],
				'last_update' => $row['last_update'],
				'company_id' => $row['company_public_id'],
				'company_title' => $row['company_title'],
				'company_logo' => $this->get_company_logo($row['company_logo']),
			);
		}

		$stmt->close();
		return $r;
	}


}