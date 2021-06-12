<?php
namespace Kapps\Model\Users;

use \Kapps\Model\General\Db;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class MyProfile extends Db
{

	public function __construct()
	{
		$this->AuthUser = new AuthUser;
		$this->me = $this->AuthUser->me();
	}


	public function get_my_profile()
	{
		$r = array();

		$query = "SELECT * FROM users";
		$db = Db::getInstance();
		$result = $db->query($query);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_array()) {
				$r = array(
					'firstname' => $row['firstname'],
					'lastname' => $row['lastname'],
					'mail' => $row['mail'],
					'mobile' => $row['mobile'],
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
		$db = Db::getInstance();
		$result = $db->query($query);

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