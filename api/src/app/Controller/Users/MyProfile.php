<?php
namespace Kapps\Controller\Users;

class MyProfile {

	public function get_my_profile()
	{
		$obj = new \Kapps\Model\Users\MyProfile();
		return $obj->get_my_profile();
	}

	public function update()
	{
		$obj = new \Kapps\Model\Users\MyProfile();
		return $obj->update($_POST);
	}

}