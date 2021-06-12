<?php
namespace Kapps\Controller\Apps;

class Files {

	public function upload()
	{
		$obj = new \Kapps\Model\Apps\Files();
		return $obj->upload($_POST, $_FILES['files']);
	}

	public function delete()
	{
		$obj = new \Kapps\Model\Apps\Files();
		return $obj->delete($_POST['app_id'], $_POST['file_id']);
	}

	public function get_file($id)
	{
		$obj = new \Kapps\Model\Apps\Files();
		return $obj->get_file($id);
	}

	public function get_app_files($id)
	{
		$obj = new \Kapps\Model\Apps\Files();
		return $obj->get_app_files($id);
	}

}