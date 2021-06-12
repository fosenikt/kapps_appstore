<?php
namespace Kapps\Controller\Companies;

class Update {

	public function update()
	{
		$obj = new \Kapps\Model\Companies\Update();
		return $obj->update($_POST);
	}

}