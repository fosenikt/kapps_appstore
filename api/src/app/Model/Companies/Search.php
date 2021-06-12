<?php
namespace Kapps\Model\Companies;

use \Kapps\Model\General\Db;
use \Kapps\Model\Companies\Companies;


/**
 * summary
 */
class Search extends Db
{

	/**
	 * summary
	 */
	public function __construct()
	{
		$this->Companies = new Companies;
	}


	public function companies($q, $limit=15)
	{
		$r = null;
		$db = Db::getInstance();


		// Check for content in search-string, and split each words into an array
		if (strlen($q) > 1)
			$qarr = explode(" ", $q);

		$query = "SELECT id, public_id, title, type, logo FROM company";

		// Set counter to 0
		$t = 0;

		// Count words in search-string
		$n = count($qarr);
		
		// Loop each words and build the query to search for each word
		if (strlen($q) > 1) {
			$query .= " WHERE ";

			foreach($qarr as $so) {
			  $query .= "(
			  	        title LIKE '%$so%' OR 
			  	        county LIKE '%$so%' OR 
			  	        org_numb LIKE '%$so%'
			  	       ) ";
			  
				
				// Add AND to earch word
				if($t++ < $n - 1)
				{
					$query .= "AND ";
				}
			}
		}
		
		// End search string
		$query .= " ORDER BY title ASC";
		$query .= " LIMIT $limit";


		$result = $db->query($query);
		$numRows = $result->num_rows;

		while ($row = $result->fetch_array()) {
			

			$r[] = array(
				'id' => $row['id'],
				'public_id' => $row['public_id'],
				'title' => $row['title'],
				'type' => $row['type'],
				'logo' => $this->Companies->get_logo($row['logo']),
			);
		}

		return $r;
	}
}