<?php
namespace Kapps\Controller\Search;

class Search {


	public function all()
	{		
		$obj = new \Kapps\Model\Search\Search();
		return $obj->all($_POST['q']);
	}

	public function apps()
	{		
		$obj = new \Kapps\Model\Search\Search();
		return $obj->apps($_POST['q']);
	}

	public function companies()
	{		
		$obj = new \Kapps\Model\Search\Search();
		return $obj->companies($_POST['q']);
	}

}