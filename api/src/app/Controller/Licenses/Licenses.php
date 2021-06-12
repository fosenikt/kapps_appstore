<?php
namespace Kapps\Controller\Licenses;

class Licenses {

	public function get_licenses()
	{		
		$obj = new \Kapps\Model\Licenses\Licenses();
		return $obj->get_licenses();
	}

	public function get_license($id)
	{		
		$obj = new \Kapps\Model\Licenses\Licenses();
		return $obj->get_license($id);
	}

}