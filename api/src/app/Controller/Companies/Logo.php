<?php
namespace Kapps\Controller\Companies;

class Logo {

	public function upload()
	{
		$obj = new \Kapps\Model\Companies\Logo();
		return $obj->upload($_POST, $_FILES['image']);
	}

}