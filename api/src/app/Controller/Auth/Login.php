<?php
namespace Kapps\Controller\Auth;

class Login {

	public function send_login_link()
	{
		$obj = new \Kapps\Model\Auth\Login();
		return $obj->send_login_link($_POST['mail']);
	}

	public function validate_code()
	{
		$obj = new \Kapps\Model\Auth\Login();
		return $obj->validate_code($_POST['mail'], $_POST['code']);
	}

	public function validate_hash()
	{
		$obj = new \Kapps\Model\Auth\Login();
		return $obj->validate_hash($_POST['user_id'], $_POST['hash']);
	}
}