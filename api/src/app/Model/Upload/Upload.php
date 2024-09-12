<?php
namespace Kapps\Model\Upload;

use Kapps\Model\Database\Db;
use \Kapps\Model\Events\Event;
use \Kapps\Model\Companies\Access;
use \Kapps\Model\Auth\User as AuthUser;
use \Gumlet\ImageResize;

class Upload
{
	private $db;
	private $me;
	private $Event;
	private $Access;

	public function __construct()
	{
		$this->db = Db::getInstance();
		$this->me = (new AuthUser())->me();
		
		$this->Event = new Event;
		$this->Access = new Access();
	}





	/**
	  * Upload company logo
	  *
	  * @param 	String	$target 	Path to folder
	  * @param 	Array 	$file 		File object
	 */
	public function upload_company_logo($p, $file)
	{

		// Check for admin access to update company
		if (!$this->Access->chk_customer_access($p['public_id'])) {
			return array('status' => 'error', 'message' => 'Manglende tilgang til organisasjon. Forsøk eventuelt å logg ut og inn igjen.');
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
			error_log('Filepath: ' . $target.$new_filename);

			try{
				$thumb = new \Gumlet\ImageResize($target.$new_filename);
				$thumb->resizeToBestFit(200, 200);
				$thumb->save($target.'_thumbs/'.$new_filename);
			} catch (\ImageResizeException $e) {
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
			} catch (\ImageResizeException $e) {
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




	/**
	 * Add uploaded logo to customer profile
	 */
	private function add_logo2customer($customer_id, $filename)
	{
		if (empty($customer_id)) {
			return array('status' => 'error', 'message' => 'Company ID not provided');
		}

		elseif (empty($filename)) {
			return array('status' => 'error', 'message' => 'Filename not provided');
		}

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('UPDATE company SET logo=? WHERE public_id=?')) {
			
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$result = $stmt->bind_param('ss', $filename, $customer_id);

			if (!$stmt) {
				return array('status' => 'error', 'message' => $this->db->error);
			}

			if (!$stmt->execute()) {
				return array('status' => 'error', 'message' => $stmt->error);
			}
		}


		$stmt->close();

		return array('status' => 'success');
	}





	/**
	  * Upload file
	  *
	  * @param 	String	$target 	Path to folder
	  * @param 	Array 	$file 		File object
	 */
	public function upload_file($target, $file, $resize_image=true)
	{

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


		// Check if target directory is writable
		if (!is_writable($target)) {
			$error = true;
			$error_msg[] = 'Target directory is not writable';

			return $this->Event->error(array(
				'title' => 'Upload: Target directory is not writable',
				'message' => 'Failed to write to target directory. Check permissions.',
				'severity' => 'high',
				'event_data' => array('target' => $target, 'file' => $file),
			));
		}


		$exp = explode('.', $file["name"]);
		$ext = strtolower(end($exp));

		//$file["name"] = str_replace('.jpeg', '.jpg', $file["name"]);

		$new_filename = time().'_'.rand(0,9999).'.'.$ext;


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
			error_log('File ' . $new_filename . ' uploaded');
		}



		// If image, run resize
		if ($resize_image) {
			if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'tiff') {
				error_log('Resizing thumb');
				error_log('Filepath: ' . $target.$file["name"]);

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
		}



		return array('status' => 'success', 'filename' => $new_filename);
	}


}