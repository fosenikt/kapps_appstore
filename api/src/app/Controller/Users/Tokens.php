<?php
namespace Kapps\Controller\Users;

class Tokens {

	public function create_token()
	{
		$obj = new \Kapps\Model\Users\Tokens();
		return $obj->create_token($_POST['user_id'], $_POST['title']);
	}

	public function get_tokens($id)
	{
		$obj = new \Kapps\Model\Users\Tokens();
		return $obj->get_tokens($id);
	}

	public function delete_token($id)
	{
		$obj = new \Kapps\Model\Users\Tokens();
		return $obj->delete_token($id);
	}


}