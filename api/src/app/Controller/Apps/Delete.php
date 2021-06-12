<?php
namespace Kapps\Controller\Apps;

class Delete {

	public function delete($id)
	{		
		$obj = new \Kapps\Model\Apps\Delete();
		return $obj->delete($id);
	}

}