<?php
namespace Kapps\Controller\Users;

class MyProfile {

	public function get_my_profile()
	{
		$obj = new \Kapps\Model\Users\MyProfile();
		return $obj->get_my_profile();
	}

}