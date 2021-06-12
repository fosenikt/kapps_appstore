<?php
namespace Kapps\Controller\Delivery;

class Methods {

	public function get_methods()
	{		
		$obj = new \Kapps\Model\Delivery\Methods();
		return $obj->get_methods();
	}

}