<?php
namespace Kapps\Controller\Apps;

class Create {

	public function add()
	{		
		$obj = new \Kapps\Model\Apps\Create();
		return $obj->add($_POST);
	}

	public function update_description()
	{		
		$obj = new \Kapps\Model\Apps\Create();
		return $obj->update_description($_POST);
	}

	public function update_installation()
	{		
		$obj = new \Kapps\Model\Apps\Create();
		return $obj->update_installation($_POST);
	}

	public function update_details()
	{		
		$obj = new \Kapps\Model\Apps\Create();
		return $obj->update_details($_POST);
	}

}