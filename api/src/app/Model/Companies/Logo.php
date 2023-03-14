<?php
namespace Kapps\Model\Companies;

use \Kapps\Model\General\Db;
use \Kapps\Model\General\Event;
use \Kapps\Model\Companies\Access;
use \Kapps\Model\Auth\User as AuthUser;
use \Gumlet\ImageResize;

/**
 * summary
 */
class Logo extends Db
{
	private $AuthUser;
	private $me;
	private $Event;
	private $Access;

	public function __construct()
	{
		$this->AuthUser = new AuthUser;
		$this->me = $this->AuthUser->me();
		
		$this->Event = new Event;
		$this->Access = new Access();
	}




	
	/**
	  * Upload file
	  *
	  * @param 	String	$target 	Path to folder
	  * @param 	Array 	$file 		File object
	 */
	public function upload($p, $file)
	{

		// Check for admin access to update company
		if (!$this->Access->chk_customer_access($p['public_id'])) {
			return array('status' => 'error', 'message' => 'Access denied');
		}



		$target = './data/companies_logo/';

		$error = false;
		$error_msg = array();


		if (!is_dir($target)) {
			$error = true;
			$error_msg[] = 'Target dir does not exist';

			return $this->Event->error(array(
				'title' => 'Upload: Target dir does not exist',
				'message' => 'Target dir does not exist',
				'severity' => 'high',
				'event_data' => array('target' => $target, 'file' => $file),
			));
		}


		$exp = explode('.', $file["name"]);
		$ext = strtolower(end($exp));

		//$file["name"] = str_replace('.jpeg', '.jpg', $file["name"]);

		$hashed_filename = hash('sha256', $this->me['customer']['public_id'].$file["name"]);
		$new_filename = $hashed_filename.'.'.$ext;


		// Upload the file
		//if (!move_uploaded_file($file["tmp_name"], $target.$file["name"])) {
		if (!move_uploaded_file($file["tmp_name"], $target.$new_filename)) {
			$error = true;
			$error_msg[] = 'Save file to folder failed';
			
			return $this->Event->error(array(
				'title' => 'Upload: Could not upload file',
				'message' => 'Target dir does not exist',
				'severity' => 'high',
				'event_data' => array('target' => $target, 'file' => $file, 'error_msg' => $error_msg),
			));
		}

		else {
			error_log('File ' . $target.$file["name"] . ' uploaded');
		}



		// If image, run resize
		if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'tiff') {
			error_log('Resizing thumb');
			//error_log('Filepath: ' . $target.$file["name"]);
			error_log('Filepath: ' . $target.$new_filename);

			try{
				$thumb = new \Gumlet\ImageResize($target.$new_filename);
				$thumb->resizeToBestFit(200, 200);
				$thumb->save($target.'_thumbs/'.$new_filename);
			} catch (ImageResizeException $e) {
				error_log("Something went wrong: " . $e->getMessage());
			}  catch (\Error $e) {
				error_log("Something went wrong: " . $e->getMessage());

				return $this->Event->error(array(
					'title' => 'Upload: Could not resize image',
					'message' => 'Exception on \Gumlet\ImageResize',
					'severity' => 'medium',
					'event_data' => array('target' => $target, 'file' => $file, 'error_msg' => $e->getMessage()),
				));
			}

			error_log('Resizing image');
			error_log('Filepath: ' . $target.$file["name"]);

			try{
				$image = new \Gumlet\ImageResize($target.$new_filename);
				$image->resizeToBestFit(1600, 1200);
				$image->save($target.$new_filename);
			} catch (ImageResizeException $e) {
				error_log("Something went wrong: " . $e->getMessage());
			}  catch (\Error $e) {
				error_log("Something went wrong: " . $e->getMessage());

				return $this->Event->error(array(
					'title' => 'Upload: Could not resize image',
					'message' => 'Exception on \Gumlet\ImageResize',
					'severity' => 'medium',
					'event_data' => array('target' => $target, 'file' => $file, 'error_msg' => $e->getMessage()),
				));
			}
		}



		//return array('status' => 'success', 'thumb' => $thumb, 'image' => $image);
		$link_logo = $this->add_logo2customer($p['public_id'], $new_filename);

		if ($link_logo['status'] == 'error') {
			return $link_logo;
		}

		return array('status' => 'success');
	}





	private function add_logo2customer($customer_id, $filename)
	{
		if (empty($customer_id)) {
			return array('status' => 'error', 'message' => 'Company ID not provided');
		}

		elseif (empty($filename)) {
			return array('status' => 'error', 'message' => 'Filename not provided');
		}

		// Create DB instance
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('UPDATE company SET logo=? WHERE public_id=?')) {
			
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$result = $stmt->bind_param('ss', $filename, $customer_id);

			if (!$stmt) {
				return array('status' => 'error', 'message' => $db->error);
			}

			if (!$stmt->execute()) {
				return array('status' => 'error', 'message' => $stmt->error);
			}
		}


		$stmt->close();

		return array('status' => 'success');
	}


}