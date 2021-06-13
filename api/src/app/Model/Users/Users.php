<?php
namespace Kapps\Model\Users;

use \Kapps\Model\General\Db;
use \Kapps\Model\General\Colors;
use \Kapps\Model\Companies\Companies;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Users extends Db
{

	public function __construct()
	{
		$this->Companies = new Companies;
		$this->Colors = new Colors;

		$this->AuthUser = new AuthUser;
		$this->me = $this->AuthUser->me();
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
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('SELECT * FROM users ORDER BY firstname ASC, lastname ASC')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			//$stmt->bind_param('s', $mail);
			$stmt->execute();
		}


		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {

			if (!empty($row['initials'])) $initials = $row['initials'];
			else $initials = $this->create_initials($row['firstname'], $row['lastname'], $row['mail']);

			if (!empty($row['color'])) $color = $row['color'];
			else $color = $this->Colors->get_random_color();

			$r[] = array(
				'id' => $row['id'],
				'o365_id' => $row['o365_id'],
				'customer' => $this->Companies->get_company_by_id($row['customer_id']),
				'firstname' => $row['firstname'],
				'lastname' => $row['lastname'],
				'initials' => $initials,
				'mail' => $row['mail'],
				'mobile' => $row['mobile'],
				'o365_token' => $row['o365_token'],
				'status' => $row['status'],
				'photo' => $row['photo'],
				'company_role' => $row['company_role'],
				'login_token' => $row['login_token'],
				'reset_token' => $row['reset_token'],
				'token_created' => $row['token_created'],
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
		//if (empty($company_public_id)) return $r;

		// Init DB connection
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('SELECT U.id, U.firstname, U.lastname, U.initials, U.mail, U.mobile, U.photo, U.company_role, U.color
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

			if (!empty($row['initials'])) $initials = $row['initials'];
			else $initials = $this->create_initials($row['firstname'], $row['lastname'], $row['mail']);

			if (!empty($row['color'])) $color = $row['color'];
			else $color = $this->Colors->get_random_color();

			$r[] = array(
				'id' => $row['id'],
				'firstname' => $row['firstname'],
				'lastname' => $row['lastname'],
				'initials' => $initials,
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
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('SELECT * FROM users WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $id);
			$stmt->execute();
		}


		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {

			if (!empty($row['initials'])) $initials = $row['initials'];
			else $initials = $this->create_initials($row['firstname'], $row['lastname'], $row['mail']);

			if (!empty($row['color'])) $color = $row['color'];
			else $color = $this->Colors->get_random_color();

			$r = array(
				'id' => $row['id'],
				'customer' => $this->Companies->get_company_by_id($row['customer_id']),
				'firstname' => $row['firstname'],
				'lastname' => $row['lastname'],
				'initials' => $initials,
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




	/**
	 * Create initials
	 * 
	 * Security: None (backend only)
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	String      $firstname    Firstname
	 * @param 	String      $lastname     Lastname
	 * @param 	String      $mail         Mail
	 * @return 	String                    Initials
	 */
	public function create_initials($firstname, $lastname, $mail)
	{
		if (!empty($firstname) && !empty($lastname)) {
			$f = substr($firstname, 0, 1);
			$l = substr($lastname, 0, 1);
			return strtoupper($f.$l);
		}

		if (!empty($firstname) && empty($lastname)) {
			$f = substr($firstname, 0, 2);
			return strtoupper($f);
		}

		if (empty($firstname) && !empty($lastname)) {
			$l = substr($lastname, 0, 2);
			return strtoupper($l);
		}


		if (!empty($mail)) {
			list($user, $domain) = explode('@', $mail);

			$split_username = explode('.', $user);

			if (is_array($split_username) && count($split_username) >= 2) {
				$f = substr($split_username[0], 0, 1);
				$l = substr(end($split_username), 0, 1);
				return strtoupper($f.$l);
			}

			else {
				return strtoupper(substr($mail, 0, 2));
			}
		}

		return 'XX';
	}

}