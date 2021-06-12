<?php
namespace Kapps\Controller\Apps;

class Publish {

	public function publish($id)
	{		
		$obj = new \Kapps\Model\Apps\Publish();
		return $obj->publish($id);
	}

	public function unpublish($id)
	{		
		$obj = new \Kapps\Model\Apps\Publish();
		return $obj->unpublish($id);
	}

}