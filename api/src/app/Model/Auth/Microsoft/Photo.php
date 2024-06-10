<?php
namespace Kapps\Model\Auth\Microsoft;

use Kapps\Model\Database\Db;
use Kapps\Model\Auth\Microsoft\TokenCache;

class Photo
{
	private $db;
	private $img_url;
	private $img_path;
	private $debug;

	public function __construct()
	{
		$this->db = Db::getInstance();
		$this->img_url = URL.'/data/profilepictures/';
		$this->img_path = $_SERVER['DOCUMENT_ROOT'].'/data/profilepictures/';

		$this->debug = true;
	}


	public function fetch_photo($upn)
	{
		error_log('Getting photo from O365');

		$tokenCache = new TokenCache;
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://graph.microsoft.com/v1.0/me/photo/$value',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer {$tokenCache->getAccessToken()}",
				"cache-control: no-cache"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);


		if ($err) {
			return array('status' => 'error', 'message' => 'Curl error', 'error' => $err);
		} else {
			$response_data = json_decode($response, true);

			if (is_array($response_data) && isset($response_data['error'])) {
				return array('status' => 'error', 'message' => 'Graph API error', 'error' => $response_data['error']);
			}

			else {
				$encoded_image = base64_encode($response);

				$save_file = $this->store_base64_image($upn, $encoded_image);

				if ($save_file['status'] == 'success') {
					return array('status' => 'success', 'path' => $save_file['path'], 'url' => $save_file['url']);
				} else {
					return $save_file;
				}
			}
		}
	}





	function getProtectedValue($obj) {
		$array = (array)$obj;
		$first_key = key($array);
		return $array[$first_key];
	}




	private function convert_filename($upn)
	{
		$filename = str_replace('@', '_', $upn);
		$filename = str_replace('.', '_', $filename);
		return $filename;
	}


	private function store_base64_image($upn, $image)
	{
		if ($this->debug) error_log('Storing O365 photo for: ' . $upn);

		if (empty($upn)) {
			return array('status' => 'error', 'message' => 'UPN for user missing');
		}

		// Save as file
		$filename = $this->convert_filename($upn);

		return $this->save_as_file($upn, $filename, $image);
	}



	private function save_as_file($upn, $filename, $data)
	{
		$error = false;

		// Check if path exists
		if (!file_exists($this->img_path)) {
			error_log('Folder path does not exist: ' . $this->img_path);
			$create_folder = @mkdir($this->img_path, 0775, true);

			if (!$create_folder) {
				if ($this->debug) error_log('Could not create upload-dir');
				$error = true;
				return array('status' => 'error', 'message' => 'Update directory does not exist and cannot be created');
			}
		}


		if (!$error) {
			$type = 'jpg';
			$data = base64_decode($data);
			$store_file = @file_put_contents($this->img_path."$filename.{$type}", $data);

			if ($store_file) {
				$add_filename = $this->add_filename_in_db($filename, $upn);
				if (!$add_filename) {
					if ($this->debug) error_log('Could not update user DB');
					return array('status' => 'error', 'message' => 'Could not update user DB');
				}
			}

			else {
				error_log('Could not store profilephoto in: ' . $this->img_path."$filename.{$type}");
				return array('status' => 'error', 'message' => 'Could not save file to disk');
			}
		}

		return array('status' => 'success', 'path' => $this->img_path.$filename, 'url' => $this->img_url.$filename);
	}


	private function add_filename_in_db($filename, $upn)
	{
		if ($this->debug) error_log('Store filename in database');
		$time_now = date('Y-m-d H:i:s');

		// Save to DB
		$query = "UPDATE users SET photo='$filename.jpg', last_update='$time_now' WHERE mail LIKE '$upn'";
		if ($this->debug) error_log($query);
		$result = $this->db->query($query);

		if ($result) {
			return true;
		} else {
			return false;
		}
	}
}