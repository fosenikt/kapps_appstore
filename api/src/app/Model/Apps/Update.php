<?php
namespace Kapps\Model\Apps;

use Kapps\Model\Database\Db;
use \Kapps\Model\Auth\User as AuthUser;
use \Kapps\Model\Apps\Get as AppsGet;

/**
 * summary
 */
class Update
{
	private $db;
	private $thisUser;

	public function __construct()
	{
		$this->db = Db::getInstance();
		$this->thisUser = (new AuthUser())->me();
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
	public function update_app($p)
	{
		// Set company ID from logged in user to use in query.
		// Used to make sure other users cannot update this app
		$company_id = $this->thisUser['customer']['public_id'];


		// Prepare MySQL statement
		$stmt = $this->db->prepare("UPDATE apps SET time_edited=?, updated_by=?, title=?, description=?, short_description=?, installation=?, license_id=?, tags=? WHERE id=? AND company_id=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($this->db->error, E_USER_ERROR);
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


		// Prepare MySQL statement
		$stmt = $this->db->prepare("UPDATE apps SET description=? WHERE id=? AND company_id=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($this->db->error, E_USER_ERROR);
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

		// Prepare MySQL statement
		$stmt = $this->db->prepare("UPDATE apps SET installation=? WHERE id=? AND company_id=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($this->db->error, E_USER_ERROR);
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

		// Prepare MySQL statement
		$stmt = $this->db->prepare("UPDATE apps SET title=?, short_description=?, license_id=?, tags=?, link_source_code=? WHERE id=? AND company_id=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($this->db->error, E_USER_ERROR);
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
		$stmt = $this->db->prepare("UPDATE apps SET updated_by=? WHERE id=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($this->db->error, E_USER_ERROR);
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
	 * Publish an application
	 * 
	 * Security: Get app to check _edit_access_
	 *
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @todo Fix the close statement, pointless to have it there after function is returned
	 *
	 * @param  	Int       $id         App ID
	 * @return  Array                 Status array
	 */
	public function publish($id)
	{

		// Get app
		$get_app = (new AppsGet())->get_app($id);
		
		// Check if user has edit access to app
		if ($get_app['edit_access'] != 1) {
			return array('status' => 'error', 'message' => 'No access');
		}

		// Set status as published
		$status = 'published';

		
		// Prepare query
		$stmt = $this->db->prepare("UPDATE apps SET updated_by=?, status=? WHERE id=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($this->db->error, E_USER_ERROR);
			return;
		}

		// Bind params to query
		$result = $stmt->bind_param("isi", $this->thisUser['id'], $status, $id);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute(); // Execute query


		// Return status
		if ($result) {
			return array('status' => 'success');
		}
		
		else {
			return array('status' => 'error', 'error' => $stmt->error);
		}

		$stmt->close();
	}






	/**
	 * Unpublish an application
	 * 
	 * Security: Get app to check _edit_access_
	 *
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @todo Fix the close statement, pointless to have it there after function is returned
	 *
	 * @param  	Int       $id         App ID
	 * @return  Array                 Status array
	 */
	public function unpublish($id)
	{
		// Get app
		$get_app = (new AppsGet())->get_app($id);
		
		// Check if user has edit access to app
		if ($get_app['edit_access'] != 1) {
			return array('status' => 'error', 'message' => 'No access');
		}

		// Set status as published
		$status = 'draft';
		
		// Prepare query
		$stmt = $this->db->prepare("UPDATE apps SET updated_by=?, status=? WHERE id=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($this->db->error, E_USER_ERROR);
			return;
		}

		// Bind params to query
		$result = $stmt->bind_param("isi", $this->thisUser['id'], $status, $id);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute(); // Execute query


		// Return status
		if ($result) {
			return array('status' => 'success');
		} else {
			error_log('Error while logging event');
			error_log($stmt->error);
			return array('status' => 'error', 'error' => $stmt->error);
		}

		$stmt->close();
	}

}