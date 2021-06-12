<?php
namespace Kapps\Model\General;

use \Kapps\Model\General\Event;
use \Gumlet\ImageResize;

class Upload
{

	public function __construct()
	{
		$this->Event = new Event;
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