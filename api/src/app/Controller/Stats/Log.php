<?php
namespace Kapps\Controller\Stats;

class Log {

	public function log()
	{
		$obj = new \Kapps\Model\Stats\Log();
		return $obj->log($_POST['type'], $_POST['entity_id']);
	}

}