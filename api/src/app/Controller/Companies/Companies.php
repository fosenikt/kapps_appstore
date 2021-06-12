<?php
namespace Kapps\Controller\Companies;

class Companies {

	public function get_companies()
	{
		$obj = new \Kapps\Model\Companies\Companies();
		return $obj->get_companies();
	}

	public function get_companies_simple()
	{
		$obj = new \Kapps\Model\Companies\Companies();
		return $obj->get_companies_simple();
	}

	public function get_company($id)
	{
		$obj = new \Kapps\Model\Companies\Companies();
		return $obj->get_company($id);
	}

}