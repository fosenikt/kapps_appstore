<?php
namespace Kapps\Model\Companies;

use \Kapps\Model\General\Db;
use \Kapps\Model\General\Colors;


/**
 * summary
 */
class Employees extends Db
{
	private $Colors;

	/**
	 * summary
	 */
	public function __construct()
	{
		$this->Colors = new Colors;
	}




	/**
	 * Get company employees
	 * Users that has logged in for that company
	 * 
	 * Security: None (backend only).
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	Int      $id    Company Public ID
	 * @return 	Array    $r     Array with company-data
	 */
	public function get_employees($id)
	{
		$r = null;
		$db = Db::getInstance();

		$status = 'active';

		// Prepare SQL
		if ($stmt = $db->prepare('SELECT U.* 
							      FROM users AS U
								  INNER JOIN company AS C ON C.id = U.customer_id
								  WHERE C.public_id LIKE ? AND U.status LIKE ?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('ss', $id, $status);
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