<?php
namespace Kapps\Controller\Companies;

class Search {

	public function companies($q)
	{
		$obj = new \Kapps\Model\Companies\Search();
		return $obj->companies($q);
	}

}