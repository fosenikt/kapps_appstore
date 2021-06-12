<?php
namespace Kapps\Controller\Auth;

class User {

	public function me()
	{
		$obj = new \Kapps\Model\Auth\User();
		return $obj->me();
	}


	public function signout()
	{
		$obj = new \Kapps\Model\Auth\User();
		return $obj->signout();
	}
}