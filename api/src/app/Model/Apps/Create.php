<?php
namespace Kapps\Model\Apps;

use \Kapps\Model\General\Db;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Create extends Db
{
	private $AuthUser;
	private $thisUser;

	public function __construct()
	{
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

		if (isset($p['license_id'])) {
			$license_id = $p['license_id'];
		}

		if (isset($p['link_source_code'])) {
			$link_source_code = $p['link_source_code'];
		}



		// Init DB connection
		$db = Db::getInstance();


		// Set MySQL charset
		$db->set_charset("utf8mb4");
		$db->query("SET NAMES 'utf8mb4'");


		// Prepare query
		$stmt = $db->prepare("INSERT INTO apps SET 
				created_by=?, updated_by=?, company_id=?, title=?, description=?, installation=?, type_id=?, license_id=?, link_source_code=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($db->error, E_USER_ERROR);
			return;
		}

		$result = $stmt->bind_param("iissssiis", $created_by, $updated_by, $company_id, $title, $description, $installation, $type_id, $license_id, $link_source_code);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute();

		// If insert to mysql is success, return status-array
		if ($result) {
			return array('status' => 'success', 'page_id' => $stmt->insert_id);
		} 
		
		else {
			return array('status' => 'error', 'error' => $stmt->error);
		}

		$stmt->close();
	}






	/**
	 * Update application description
	 * Used for description (modal) form in frontend
	 * 
	 * Security: Logged in user must be in same company as application
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @todo Fix the close statement, pointless to have it there after function is returned
	 * 
	 * @return 	Array    $p     Input parameters
	 * @return 	Array    $r     Status array
	 */
	public function update_description($p)
	{
		// Set company ID from logged in user to use in query.
		// Used to make sure other users cannot update this app
		$company_id = $this->thisUser['customer']['public_id'];


		// Init DB connection and set charset
		$db = Db::getInstance();
		$db->set_charset("utf8mb4");
		$db->query("SET NAMES 'utf8mb4'");


		// Prepare MySQL statement
		$stmt = $db->prepare("UPDATE apps SET description=? WHERE id=? AND company_id=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($db->error, E_USER_ERROR);
			return;
		}

		// Bind parameters to query
		$result = $stmt->bind_param("sis", $p['description'], $p['id'], $company_id);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute(); // Execute query


		// If MySQL updated successfully, return status array
		if ($result) {
			$this->set_updated_by($p['id']); // Set updated by
			return array('status' => 'success');
		} 
		
		else {
			return array('status' => 'error', 'error' => $stmt->error);
		}

		$stmt->close();
	}






	/**
	 * Update application installation text
	 * Used for installation form (modal) in frontend
	 * 
	 * Security: Logged in user must be in same company as application
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @todo Fix the close statement, pointless to have it there after function is returned
	 * 
	 * @return 	Array    $p     Input parameters
	 * @return 	Array    $r     Status array
	 */
	public function update_installation($p)
	{
		// Set company ID from logged in user to use in query.
		// Used to make sure other users cannot update this app
		$company_id = $this->thisUser['customer']['public_id'];


		// Init DB connection and set charset
		$db = Db::getInstance();
		$db->set_charset("utf8mb4");
		$db->query("SET NAMES 'utf8mb4'");


		// Prepare MySQL statement
		$stmt = $db->prepare("UPDATE apps SET installation=? WHERE id=? AND company_id=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($db->error, E_USER_ERROR);
			return;
		}

		// Bind parameters to query
		$result = $stmt->bind_param("sis", $p['installation'], $p['id'], $company_id);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute(); // Execute query

		// If MySQL updated successfully, return status array
		if ($result) {
			$this->set_updated_by($p['id']);
			return array('status' => 'success');
		} 
		
		else {
			return array('status' => 'error', 'error' => $stmt->error);
		}

		$stmt->close();
	}






	/**
	 * Update application details
	 * Used for details form (sidebar) in frontend
	 * 
	 * Security: Logged in user must be in same company as application
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @todo Fix the close statement, pointless to have it there after function is returned
	 * 
	 * @return 	Array    $p     Input parameters
	 * @return 	Array    $r     Status array
	 */
	public function update_details($p)
	{
		// Set company ID from logged in user to use in query.
		// Used to make sure other users cannot update this app
		$company_id = $this->thisUser['customer']['public_id'];


		// Init DB connection and set charset
		$db = Db::getInstance();
		$db->set_charset("utf8mb4");
		$db->query("SET NAMES 'utf8mb4'");


		// Prepare MySQL statement
		$stmt = $db->prepare("UPDATE apps SET title=?, short_description=?, license_id=?, tags=?, link_source_code=? WHERE id=? AND company_id=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($db->error, E_USER_ERROR);
			return;
		}

		// Bind parameters to query
		$result = $stmt->bind_param("ssissis", $p['title'], $p['short_description'], $p['license_id'], $p['tags'], $p['link_source_code'], $p['id'], $company_id);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute(); // Execute query

		if ($result) {
			$this->set_updated_by($p['id']);
			return array('status' => 'success');
		}
		
		else {
			return array('status' => 'error', 'error' => $stmt->error);
		}

		$stmt->close();
	}






	/**
	 * Set updated by
	 * Only sets updated by, to know how updated the application last
	 * 
	 * Security: Private
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @todo Fix the close statement, pointless to have it there after function is returned
	 * 
	 * @return 	Int      $id    App ID
	 * @return 	Array    $r     Status array
	 */
	private function set_updated_by($id)
	{
		// Init DB connection
		$db = Db::getInstance();

		$stmt = $db->prepare("UPDATE apps SET updated_by=? WHERE id=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($db->error, E_USER_ERROR);
			return;
		}

		$result = $stmt->bind_param("ii", $this->thisUser['id'], $id);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute();

		if ($result) {
			return array('status' => 'success');
		} else {
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
		$db = Db::getInstance();
		$result = $db->query($query);

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