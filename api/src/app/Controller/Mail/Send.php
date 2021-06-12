<?php
namespace Kapps\Controller\Mail;

class Send {

	public function send()
	{
		$obj = new \Kapps\Model\Mail\Send();
		return $obj->send();
	}

}