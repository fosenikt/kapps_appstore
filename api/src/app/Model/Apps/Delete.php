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

		// Initialize SQL and parameters
		if ($get_app['status'] == 'deleted') {
			// SQL to delete the application completely
			$sql = "DELETE FROM apps WHERE id=?";
			$params = array("i", $id);
		} else {
			// SQL to update the status to deleted
			$sql = "UPDATE apps SET updated_by=?, status=? WHERE id=?";
			$status = 'deleted';
			$params = array("isi", $this->thisUser['id'], $status, $id);
		}

		// Prepare statement
		$stmt = $this->db->prepare($sql);
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($this->db->error, E_USER_ERROR);
			return;
		}

		// Bind parameters to query
		$result = $stmt->bind_param(...$params);
		if ($result === false) {
			error_log($stmt->error);
			return array('status' => 'error', 'error' => $stmt->error);
		}

		// Execute query
		$result = $stmt->execute();
		$stmt->close();

		// Return status
		if ($result) {
			return array('status' => 'success', 'message' => $get_app['status'] == 'deleted' ? 'Application deleted completely' : 'Application status set to deleted');
		} else {
			return array('status' => 'error', 'error' => $stmt->error);
		}
	}


}