<?php
namespace Kapps\Controller\Apps;

class Company {

	public function get_app($id)
	{		
		$obj = new \Kapps\Model\Apps\Company();
		return $obj->get_app($id);
	}

	public function get_apps()
	{		
		$obj = new \Kapps\Model\Apps\Company();
		return $obj->get_apps();
	}

}