<?php
namespace Kapps\Model\Apps;

use \Kapps\Model\General\Db;
use \Kapps\Model\Apps\Apps;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Publish extends Db
{

	public function __construct()
	{
		$this->Apps = new Apps;
		$this->AuthUser = new AuthUser;
		$this->thisUser = $this->AuthUser->me();
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
		$get_app = $this->Apps->get_app($id);
		
		// Check if user has edit access to app
		if ($get_app['edit_access'] != 1) {
			return array('status' => 'error', 'message' => 'No access');
		}

		// Set status as published
		$status = 'published';

		
		// Init DB connection
		$db = Db::getInstance();
		
		// Prepare query
		$stmt = $db->prepare("UPDATE apps SET updated_by=?, status=? WHERE id=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($db->error, E_USER_ERROR);
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
		$get_app = $this->Apps->get_app($id);
		
		// Check if user has edit access to app
		if ($get_app['edit_access'] != 1) {
			return array('status' => 'error', 'message' => 'No access');
		}

		// Set status as published
		$status = 'draft';

		
		// Init DB connection
		$db = Db::getInstance();
		
		// Prepare query
		$stmt = $db->prepare("UPDATE apps SET updated_by=?, status=? WHERE id=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($db->error, E_USER_ERROR);
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