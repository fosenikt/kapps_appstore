<?php
namespace Kapps\Controller\Companies;

class Delete {

	public function delete($id)
	{
		$obj = new \Kapps\Model\Companies\Delete();
		return $obj->delete($id);
	}

}