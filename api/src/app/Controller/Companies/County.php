<?php
namespace Kapps\Controller\Companies;

class County {

	public function get_counties()
	{
		$obj = new \Kapps\Model\Companies\County();
		return $obj->get_counties();
	}

}