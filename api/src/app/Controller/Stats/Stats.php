<?php
namespace Kapps\Controller\Stats;

class Stats {

	public function num_published()
	{
		$obj = new \Kapps\Model\Stats\Stats();
		return $obj->num_published();
	}

	public function last_published()
	{
		$obj = new \Kapps\Model\Stats\Stats();
		return $obj->last_published();
	}

	public function most_popular_apps()
	{
		$obj = new \Kapps\Model\Stats\Stats();
		return $obj->most_popular_apps();
	}

}