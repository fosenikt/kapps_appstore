<?php
namespace Kapps\Model\Apps;


/**
 * summary
 */
class Utils
{
	private $image_base_url;
	private $image_base_path;
	private $image_none;

	public function __construct()
	{
		$this->image_base_url = '//'.URL.'/data/apps/';
		$this->image_base_path = '/var/www/html/data/apps/';
		$this->image_none = '//'.FRONTEND_HOST.'/assets/images/icons/rpa_default.png';
	}



	public function get_app_image($filename, $app_id)
	{
		if (empty($filename)) {
			$primary_image = array(
				'image' => $this->image_none,
				'thumb' => $this->image_none,
			);
		} else {
			if (file_exists($this->image_base_path.$app_id.'/'.$filename)) {
				$primary_image = array(
					'image' => $this->image_base_url.$app_id.'/'.$filename,
					'thumb' => $this->image_base_url.$app_id.'/_thumbs/'.$filename,
				);
			} else {
				$primary_image = array(
					'image' => $this->image_none,
					'thumb' => $this->image_none,
				);
			}
		}

		return $primary_image;
	}





	public function get_company_logo($filename)
	{
		$exp = explode('.', $filename);
		$ext = strtolower(end($exp));

		if (empty($filename)) {
			$logo = array(
				'image' => '/assets/images/icons/building.svg',
				'thumb' => '/assets/images/icons/building.svg'
			);
		} else {
			if ($ext != 'svg' && $ext != 'webp') {
				$logo = array(
					'image' => '//'.URL.'/data/companies_logo/'.$filename,
					'thumb' => '//'.URL.'/data/companies_logo/_thumbs/'.$filename
				);
			} else {
				$logo = array(
					'image' => '//'.URL.'/data/companies_logo/'.$filename,
					'thumb' => '//'.URL.'/data/companies_logo/'.$filename
				);
			}
		}

		return $logo;
	}


}