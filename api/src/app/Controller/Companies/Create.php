<?php
namespace Kapps\Controller\Companies;

class Create {

	public function create()
	{
		$obj = new \Kapps\Model\Companies\Create();
		return $obj->create($_POST);
	}

}