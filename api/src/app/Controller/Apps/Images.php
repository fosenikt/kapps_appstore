<?php
namespace Kapps\Controller\Apps;

class Images {

	public function upload()
	{
		$obj = new \Kapps\Model\Apps\Images();
		return $obj->upload($_POST, $_FILES['images']);
	}

	public function set_primary_image()
	{
		$obj = new \Kapps\Model\Apps\Images();
		return $obj->set_primary_image($_POST['id'], $_POST['image']);
	}

	public function delete_image()
	{
		$obj = new \Kapps\Model\Apps\Images();
		return $obj->delete_image($_POST['app_id'], $_POST['filename']);
	}

}