<?php
namespace Kapps\Controller\Search;

class Search {

	public function apps()
	{		
		$obj = new \Kapps\Model\Search\Search();
		return $obj->apps($_POST['q']);
	}

}