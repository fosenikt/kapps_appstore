<?php
namespace Kapps\Model\Users;

use Kapps\Model\Database\Db;
use \Kapps\Model\Companies\Get as CompaniesGet;
use \Kapps\Model\Auth\User as AuthUser;
use \Kapps\Model\Utils\Colors;
use \Kapps\Model\Users\Utils as UsersUtils;

/**
 * summary
 */
class Get
{
	private $db;
	private $Companies;
	private $me;

	public function __construct()
	{
		$this->db = Db::getInstance();
		$this->me = (new AuthUser())->me();

		$this->Companies = new CompaniesGet;
	}




	/**
	 * Get all users
	 * 
	 * Security: Checks if user is admin
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	Int      $id    App ID
	 * @return 	Array    $r     Array with app-data (see output)
	 */
	public function get_users()
	{

		if ($this->me['admin'] != 1) {
			return array('status' => 'error', 'message' => 'No access');
		}

		$r = null;

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('SELECT * FROM users ORDER BY firstname ASC, lastname ASC')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			//$stmt->bind_param('s', $mail);
			$stmt->execute();
		}


		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			if (!empty($row['color'])) $color = $row['color'];
			else $color = Colors::get_random_color();

			$r[] = array(
				'id' => $row['id'],
				'o365_id' => $row['o365_id'],
				'customer' => $this->Companies->get_company_by_id($row['customer_id']),
				'firstname' => $row['firstname'],
				'lastname' => $row['lastname'],
				'initials' => UsersUtils::initials($row['firstname'], $row['lastname'], $row['mail']),
				'displayname' => UsersUtils::displayname($row['firstname'], $row['lastname'], $row['mail']),
				'mail' => $row['mail'],
				'mobile' => $row['mobile'],
				//'o365_token' => $row['o365_token'],
				'status' => $row['status'],
				'photo' => $row['photo'],
				'company_role' => $row['company_role'],
				//'login_token' => $row['login_token'],
				//'reset_token' => $row['reset_token'],
				//'token_created' => $row['token_created'],
				'last_update' => $row['last_update'],
				'system_user' => $row['system_user'],
				'admin' => $row['admin'],
				'color' => $color,
			);
		}

		return $r;
	}




	/**
	 * Get all company users
	 * 
	 * Security: None (Backend only)
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	Int      $company_public_id    Company Public ID
	 * @return 	Array    $r                    Array with app-data (see output)
	 */
	public function get_company_users($company_public_id)
	{
		$r = null;

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('SELECT U.id, U.firstname, U.lastname, U.initials, U.mail, U.mobile, U.photo, U.company_role, U.color
							      FROM users AS U 
								  INNER JOIN company AS C ON U.customer_id = C.id
								  WHERE C.public_id LIKE ? 
								  ORDER BY U.firstname ASC, U.lastname ASC')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('s', $company_public_id);
			$stmt->execute();
		}


		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			if (!empty($row['color'])) $color = $row['color'];
			else $color = Colors::get_random_color();

			$r[] = array(
				'id' => $row['id'],
				'firstname' => $row['firstname'],
				'lastname' => $row['lastname'],
				'initials' => UsersUtils::initials($row['firstname'], $row['lastname'], $row['mail']),
				'displayname' => UsersUtils::displayname($row['firstname'], $row['lastname'], $row['mail']),
				'mail' => $row['mail'],
				'mobile' => $row['mobile'],
				'photo' => $row['photo'],
				'company_role' => $row['company_role'],
				'color' => $color,
			);
		}

		return $r;
	}




	/**
	 * Get all users
	 * 
	 * Security: None (backend only)
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	Int      $id    App ID
	 * @return 	Array    $r     Array with app-data (see output)
	 */
	public function get_user($id)
	{
		$r = null;

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('SELECT * FROM users WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $id);
			$stmt->execute();
		}


		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			if (!empty($row['color'])) $color = $row['color'];
			else $color = Colors::get_random_color();

			$r = array(
				'id' => $row['id'],
				'customer' => $this->Companies->get_company_by_id($row['customer_id']),
				'firstname' => $row['firstname'],
				'lastname' => $row['lastname'],
				'initials' => UsersUtils::initials($row['firstname'], $row['lastname'], $row['mail']),
				'displayname' => UsersUtils::displayname($row['firstname'], $row['lastname'], $row['mail']),
				'mail' => $row['mail'],
				'mobile' => $row['mobile'],
				'company_role' => $row['company_role'],
				'photo' => $row['photo'],
				'last_update' => $row['last_update'],
				'color' => $color,
			);

			if ($this->me['admin'] == 1) {
				if (!empty($row['customer_id'])) {
					$r['customer']['id'] = $row['customer_id'];
				}
				$r['admin'] = $row['admin'];
				$r['status'] = $row['status'];
			}
		}

		return $r;
	}



	




	public function get_my_profile()
	{
		$r = array();

		$query = "SELECT * FROM users";
		$result = $this->db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r = array(
					'firstname' => $row['firstname'],
					'lastname' => $row['lastname'],
					'mail' => $row['mail'],
					'mobile' => $row['mobile'],
					'company_role' => $row['company_role'],
					'photo' => $row['photo'],
					'last_update' => $row['last_update'],
					'status' => $row['status'],
					'company' => $this->get_my_company($row['customer_id']),
				);
			}
		}

		return $r;
	}



	public function get_my_company($id)
	{
		$r = array();

		$query = "SELECT * FROM company WHERE id='$id'";
		$result = $this->db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r = array(
					'public_id' => $row['public_id'],
					'domain' => $row['domain'],
					'title' => $row['title'],
					'logo' => $row['logo'],
				);
			}
		}

		return $r;
	}

}