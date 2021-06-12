<?php
namespace Kapps\Controller\Companies;

class Municipality {

	public function get_municipalities()
	{
		$obj = new \Kapps\Model\Companies\Municipality();
		return $obj->get_municipalities();
	}

}