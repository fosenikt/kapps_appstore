<?php
namespace Kapps\Model\Apps;

use Kapps\Model\Database\Db;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Create
{
	private $db;
	private $AuthUser;
	private $thisUser;

	public function __construct()
	{
		$this->db = Db::getInstance();

		$this->AuthUser = new AuthUser;
		$this->thisUser = $this->AuthUser->me();
	}






	/**
	 * Create application
	 * 
	 * Security: None (Backend only)
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @todo Fix the close statement, pointless to have it there after function is returned
	 * 
	 * @return 	Array    $p     Input parameters
	 * @return 	Array    $r     Status array
	 */
	public function add($p)
	{

		// Declare and set variables for logged in user
		$created_by = $this->thisUser['id'];
		$updated_by = $this->thisUser['id'];
		$company_id = $this->thisUser['customer']['public_id'];



		// Get and set input data for query
		if (isset($p['type_id'])) {
			$type_id = $p['type_id'];
		} else {
			return array('status' => 'error', 'message' => 'Type ID not provided');
		}

		$type_data = $this->get_app_type($type_id);

		if (isset($p['title'])) {
			$title = $p['title'];
		}

		if (isset($p['short_description'])) {
			$short_description = $p['short_description'];
		} else {
			$short_description = $type_data['short_description'];
		}

		if (isset($p['description'])) {
			$description = $p['description'];
		} else {
			$description = $type_data['template_desc'];
		}

		if (isset($p['installation'])) {
			$installation = $p['installation'];
		} else {
			$installation = '';
		}

		if (isset($p['license_id']) && !empty($p['license_id'])) {
			$license_id = $p['license_id'];
		} else {
			return array('status' => 'error', 'message' => 'Lisens ikke valgt');
		}

		if (isset($p['link_source_code'])) {
			$link_source_code = $p['link_source_code'];
		}


		// Prepare query
		$stmt = $this->db->prepare("INSERT INTO apps SET 
				created_by=?, updated_by=?, company_id=?, title=?, description=?, short_description=?, installation=?, type_id=?, license_id=?, link_source_code=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($this->db->error, E_USER_ERROR);
			return;
		}

		$result = $stmt->bind_param("iisssssiis", $created_by, $updated_by, $company_id, $title, $description, $short_description, $installation, $type_id, $license_id, $link_source_code);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute();

		// If insert to mysql is success, return status-array
		if ($result) {
			return array('status' => 'success', 'app_id' => $stmt->insert_id);
		} 
		
		else {
			return array('status' => 'error', 'error' => $stmt->error);
		}

		$stmt->close();
	}






	/**
	 * Get app type
	 * Used to get app type _template_desc_ to insert in new application.
	 * E.g. description for RPA process.
	 * 
	 * Security: Private
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @todo Change to prepared mysql
	 * 
	 * @return 	Int      $id    App ID
	 * @return 	Array    $r     Status array
	 */
	private function get_app_type($id)
	{
		$r = array(); // Declare return-array

		// Query apps
		$query = "SELECT * FROM app_types WHERE id='$id'";
		$result = $this->db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'fa_icon' => $row['fa_icon'],
					'default_image' => $row['default_image'],
					'template_desc' => $row['template_desc'],
				);
			}
		}

		return $r;
	}

}