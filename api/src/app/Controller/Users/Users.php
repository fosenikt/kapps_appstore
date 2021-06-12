<?php
namespace Kapps\Controller\Users;

class Users {

	public function get_users()
	{
		$obj = new \Kapps\Model\Users\Users();
		return $obj->get_users();
	}


	public function get_user($id)
	{
		$obj = new \Kapps\Model\Users\Users();
		return $obj->get_user($id);
	}


	public function get_company_users($id)
	{
		$obj = new \Kapps\Model\Users\Users();
		return $obj->get_company_users($id);
	}

}