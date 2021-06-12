<?php
namespace Kapps\Controller\Users;

class Admin {

	public function create_user()
	{
		$obj = new \Kapps\Model\Users\Admin();
		return $obj->create_user($_POST);
	}

	public function update_user()
	{
		$obj = new \Kapps\Model\Users\Admin();
		return $obj->update_user($_POST);
	}

	public function delete_user($id)
	{
		$obj = new \Kapps\Model\Users\Admin();
		return $obj->delete_user($id);
	}

}