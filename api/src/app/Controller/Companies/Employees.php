<?php
namespace Kapps\Controller\Companies;

class Employees {

	public function get_employees($id)
	{
		$obj = new \Kapps\Model\Companies\Employees();
		return $obj->get_employees($id);
	}

}