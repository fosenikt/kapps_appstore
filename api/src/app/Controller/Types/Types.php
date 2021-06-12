<?php
namespace Kapps\Controller\Types;

class Types {

	public function get_types()
	{
		$obj = new \Kapps\Model\Types\Types();
		return $obj->get_types();
	}

}