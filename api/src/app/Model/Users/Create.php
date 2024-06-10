<?php
namespace Kapps\Model\Users;

use Kapps\Model\Database\Db;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Create
{
	private $db;
	private $AuthUser;
	private $me;

	public function __construct()
	{
		$this->db = Db::getInstance();
		$this->AuthUser = new AuthUser;
		$this->me = $this->AuthUser->me();
	}




	/**
	 * Create user
	 * 
	 * Security: Checks if user is admin
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	Int      $id    App ID
	 * @return 	Array    $r     Array with app-data (see output)
	 */
	public function create_user($p)
	{
		if ($this->me['admin'] != 1) {
			return array('status' => 'error', 'message' => 'No access');
		}

		$fields = ['firstname', 'lastname', 'mail', 'mobile', 'company_role', 'status', 'customer_id', 'admin'];
		$data = [];

		foreach ($fields as $field) {
			if (isset($p[$field]) && !empty($p[$field])) {
				$data[$field] = $p[$field];
			} else {
				$data[$field] = ($field == 'customer_id' || $field == 'admin') ? null : '';
			}
		}

		// Check required fields
		if (empty($data['mail'])) {
			return array('status' => 'error', 'message' => 'Email is required');
		}

		if (empty($data['customer_id'])) {
			return array('status' => 'error', 'message' => 'Customer ID is required');
		}

		if (!isset($data['admin']) || empty($data['admin'])) {
			$data['admin'] = 0;
		}

		// Check for duplicate email
		if ($stmt = $this->db->prepare('SELECT id FROM users WHERE mail = ?')) {
			$stmt->bind_param('s', $data['mail']);
			$stmt->execute();
			$stmt->store_result();

			if ($stmt->num_rows > 0) {
				$stmt->close();
				return array('status' => 'error', 'message' => 'Duplicate email');
			}
			$stmt->close();
		} else {
			return array('status' => 'error', 'message' => $this->db->error);
		}

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('INSERT INTO users SET customer_id=?, firstname=?, lastname=?, mail=?, mobile=?, company_role=?, status=?, admin=?')) {
			$stmt->bind_param(
				'issssssi',
				$data['customer_id'],
				$data['firstname'],
				$data['lastname'],
				$data['mail'],
				$data['mobile'],
				$data['company_role'],
				$data['status'],
				$data['admin']
			);

			if (!$stmt) {
				return array('status' => 'error', 'message' => $this->db->error);
			}

			if (!$stmt->execute()) {
				if ($stmt->errno == 1062) {
					return array('status' => 'error', 'message' => 'Duplicate entry');
				} else {
					return array('status' => 'error', 'message' => $stmt->error);
				}
			}
		} else {
			return array('status' => 'error', 'message' => $this->db->error);
		}

		if ($stmt->affected_rows == 1) {
			$status = array('status' => 'success', 'id' => $stmt->insert_id);
		} else {
			$status = array('status' => 'error', 'message' => 'Error creating user', 'msgid' => 2);
		}

		$stmt->close();
		return $status;
	}

}

?>