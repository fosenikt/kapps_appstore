<?php
namespace Kapps\Model\Companies;

use Kapps\Model\Database\Db;
use \Kapps\Model\Companies\Access;

/**
 * Class Delete
 *
 * Handles deletion of companies and related data from the database.
 */
class Delete
{
    private $db;
    private $Access;

    /**
     * Constructor initializes database connection and access control.
     */
    public function __construct()
    {
        $this->db = Db::getInstance();
        $this->Access = new Access();
    }

	

    /**
     * Deletes a company and its related data (users, applications).
     *
     * @param string $public_id The public identifier of the company to be deleted.
     * @return array Status of the delete operation.
     */
    public function delete($public_id)
    {
        // Check for admin access to delete company
        if (!$this->Access->isAdmin()) {
            return ['status' => 'error', 'message' => 'Access denied'];
        }

        $error = false;

        // Get company data
        $get_company = $this->get_company($public_id);
        if (!$get_company) {
            return ['status' => 'error', 'message' => 'Company not found'];
        }

        // Delete the company
        $delete_company = $this->delete_company($public_id);
        if (is_array($delete_company) && $delete_company['status'] === 'error') {
            $error = true;
            $message = $delete_company['message'];
        }

        // Delete company users
        if (!$error) {
            $delete_users = $this->delete_users($get_company['id']);
            if (is_array($delete_users) && $delete_users['status'] === 'error') {
                $error = true;
                $message = $delete_users['message'];
            }
        }

        // Delete applications
        if (!$error) {
            $delete_apps = $this->delete_apps($get_company['id']);
            if (is_array($delete_apps) && $delete_apps['status'] === 'error') {
                $error = true;
                $message = $delete_apps['message'];
            }
        }

        if ($error) {
            return ['status' => 'error', 'message' => $message];
        }

        return ['status' => 'success'];
    }



    /**
     * Retrieves company data by public ID.
     *
     * @param string $id The public ID of the company.
     * @return array|null Company data or null if not found.
     */
    private function get_company($id)
    {
        $r = null;

        if ($stmt = $this->db->prepare('SELECT * FROM company WHERE public_id=?')) {
            $stmt->bind_param('s', $id);
            $stmt->execute();

            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $r = [
                    'id' => $row['id'],
                    'public_id' => $row['public_id'],
                    'title' => $row['title'],
                ];
            }
        }

        return $r;
    }

    /**
     * Deletes a company by its public ID.
     *
     * @param string $public_id The public ID of the company.
     * @return array|bool Status of the deletion or true if successful.
     */
    private function delete_company($public_id)
    {
        if ($stmt = $this->db->prepare('DELETE FROM company WHERE public_id=?')) {
            $stmt->bind_param('s', $public_id);

            if (!$stmt->execute()) {
                return ['status' => 'error', 'message' => $stmt->error];
            }
        }

        $status = ($stmt->affected_rows > 0) 
            ? true 
            : ['status' => 'error', 'message' => 'Error deleting company', 'msgid' => 2];

        $stmt->close();
        return $status;
    }



    /**
     * Deletes users related to a specific company.
     *
     * @param int $customer_id The internal ID of the company.
     * @return array|bool Status of the deletion or true if successful.
     */
    private function delete_users($customer_id)
	{
		if ($stmt = $this->db->prepare('DELETE FROM users WHERE customer_id=?')) {
			$stmt->bind_param('i', $customer_id);

			if (!$stmt->execute()) {
				return ['status' => 'error', 'message' => $stmt->error];
			}
		}

		// Return true even if no rows were affected to account for no users existing
		$status = ($stmt->affected_rows >= 0) 
			? true 
			: ['status' => 'error', 'message' => 'Error deleting users', 'msgid' => 3];

		$stmt->close();
		return $status;
	}



    /**
     * Deletes applications related to a specific company.
     *
     * @param int $public_id The internal ID of the company.
     * @return array|bool Status of the deletion or true if successful.
     */
	private function delete_apps($public_id)
	{
		if ($stmt = $this->db->prepare('DELETE FROM apps WHERE company_id=?')) {
			$stmt->bind_param('s', $public_id);

			if (!$stmt->execute()) {
				return ['status' => 'error', 'message' => $stmt->error];
			}
		}

		// Return true even if no rows were affected to account for no applications existing
		$status = ($stmt->affected_rows >= 0) 
			? true 
			: ['status' => 'error', 'message' => 'Error deleting apps', 'msgid' => 4];

		$stmt->close();
		return $status;
	}
}
