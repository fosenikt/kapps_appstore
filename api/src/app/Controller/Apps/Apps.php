<?php
namespace Kapps\Controller\Apps;

class Apps {

	public function get_app($id)
	{		
		$obj = new \Kapps\Model\Apps\Apps();
		return $obj->get_app($id);
	}

	public function get_apps()
	{		
		$obj = new \Kapps\Model\Apps\Apps();
		return $obj->get_apps($_GET);
	}

}