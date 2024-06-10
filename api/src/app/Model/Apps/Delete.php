<?php
namespace Kapps\Model\Apps;

use Kapps\Model\Database\Db;
use \Kapps\Model\Apps\Get as AppsGet;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Delete
{
	private $db;
	private $Apps;
	private $AuthUser;
	private $thisUser;

	public function __construct()
	{
		$this->db = Db::getInstance();

		$this->Apps = new AppsGet;
		$this->AuthUser = new AuthUser;
		$this->thisUser = $this->AuthUser->me();
	}






	/**
	 * Delete application
	 * 
	 * Security: Fetched application (get_app()) and checks for _edit_access_
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @todo Fix the close statement, pointless to have it there after function is returned
	 * 
	 * @return 	Int      $id    App ID
	 * @return 	Array    $r     Status array
	 */
	public function delete($id)
	{
		
		// Get application to check permission
		$get_app = $this->Apps->get_app($id);
		if ($get_app['edit_access'] != 1) {
			return array('status' => 'error', 'message' => 'No access');
		}

		// Set status to set
		$status = 'deleted';
		
		// Prepare statement
		$stmt = $this->db->prepare("UPDATE apps SET updated_by=?, status=? WHERE id=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($this->db->error, E_USER_ERROR);
			return;
		}

		// Bind parameters to query
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

}