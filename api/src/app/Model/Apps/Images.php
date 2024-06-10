<?php
namespace Kapps\Model\Apps;

use Kapps\Model\Database\Db;
use \Kapps\Model\Events\Event;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Images
{
	private $db;
	private $Event;
	private $thisUser;
	private $image_base_path;
	private $image_base_url;
	private $image_none;

	public function __construct()
	{
		$this->db = Db::getInstance();
		$this->thisUser = (new AuthUser())->me();

		$this->Event = new Event();

		$this->image_base_path = $_SERVER['DOCUMENT_ROOT'].'/data/apps/';
		$this->image_base_url = '//'.URL.'/data/apps/';
		$this->image_none = $_SERVER['DOCUMENT_ROOT'].'/assets/images/icons/rpa_default.png';
	}






	/**
	 * Upload images
	 * Files are actually uploaded in \Kapps\Model\General\Upload
	 * 
	 * Security: Check app access
	 *
	 * @author Robert Andresen <ra@fosenikt.no>
	 *
	 * @param  	Array    $p         Parameters like $p['app_id']
	 * @param   Array    $files     $_FILES array from POST
	 * @return  Array               Status array
	 */
	public function upload($p, $files)
	{
		// Check if app id exist in parameters
		if (!isset($p['app_id']) || empty($p['app_id'])) {
			return (new Event())->error(array(
				'title' => 'App: No app ID provided for image upload',
				'message' => "Ingen app ID",
				'severity' => 'high',
				'event_data' => array('params' => $p, 'files' => $files),
			));
		}


		// Check if user has access to app this file is linked to
		if (!$this->chk_app_access($p['app_id'])) {
			return array('status' => 'error', 'message' => 'Access denied');
		}


		// Check if user has access to app this file is linked to
		$target_base_dir = $_SERVER['DOCUMENT_ROOT'].'/data/apps/'.$p['app_id'].'/';
		$target_thumb_dir = $_SERVER['DOCUMENT_ROOT'].'/data/apps/'.$p['app_id'].'/_thumbs/';


		// Check if path exists
		// Create folder if not
		if (!file_exists($target_base_dir)) {
			$create_folder = @mkdir($target_base_dir, 0775, true);

			if (!$create_folder) {
				error_log('Could not create upload-dir');
				$error = true;

				return $this->Event->error(array(
					'title' => 'Merchandise: Could not create directory',
					'message' => "Klarte ikke å opprette mappe",
					'severity' => 'high',
					'event_data' => array('params' => $p, 'files' => $files, 'target_base_dir' => $target_base_dir, 'target_thumb_dir' => $target_thumb_dir),
				));
			}
		}


		// Declare some variables
		$error = false;
		$error_message = '';
		$num_file_total = 0;
		$num_file_uploaded = 0;

		// Create merc dir if not exist
		if (!is_dir($target_thumb_dir)) {
			$create_thumb_dir = mkdir($target_thumb_dir, 0775);

			if (!$create_thumb_dir) {
				$error = true;

				return $this->Event->error(array(
					'title' => 'App images: Could not create _thumb directory',
					'message' => "Klarte ikke å opprette mappe for thumbnails",
					'severity' => 'high',
					'event_data' => array('params' => $p, 'files' => $files, 'target_base_dir' => $target_base_dir, 'target_thumb_dir' => $target_thumb_dir),
				));
			}
		}

		
		// Set new object to upload file
		$upload = new \Kapps\Model\Upload\Upload;


		// If multiple files
		if (is_array($files)) {

			// Rebuild array
			for ($i = 0; $i < count($files['name']); $i++) {
				$rebuild_files[] = array(
					'name' => $files['name'][$i],
					'type' => $files['type'][$i],
					'tmp_name' => $files['tmp_name'][$i],
					'error' => $files['error'][$i],
					'size' => $files['size'][$i],
				);
			}

				


			// Loop files and upload them
			foreach ($rebuild_files as $key => $file) {
				$num_file_total++;

				// Get file info
				$imageFileType = strtolower(pathinfo($target_base_dir.$file['name'],PATHINFO_EXTENSION));

				// Check if file is an image
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "webp" ) {
					$upload_files[$file['name']] = 'Format not allowed';
					$error = true;
					$error_message = "File $imageFileType not allowed";

					// Return event if not an image
					return $this->Event->error(array(
						'title' => 'Merchandise: Wrong image format',
						'message' => "Filformat $imageFileType er ikke tillatt",
						'severity' => 'high',
						'event_data' => array('params' => $p, 'files' => $files),
					));

				}
				
				// Do the actuall upload
				else {
					$upload_files[$file['name']] = $upload->upload_file($target_base_dir, $file);
					$num_file_uploaded++;
				}
			}
		}


		// Start returning statuses
		else {
			$error = true;
			$error_message = "No files";

			return $this->Event->error(array(
				'title' => 'Image: No files to upload',
				'message' => "Fant ingen filer å laste opp",
				'severity' => 'medium',
				'event_data' => array('params' => $p, 'files' => $files),
			));
		}


		// Success
		if (!$error) {
			$this->Event->add(array(
				'domain' => 'apps',
				'event_type' => 'ImageUpload',
				'entity_id' => $p['app_id'],
				'entity_tag' => 'app-'.$p['app_id'],
				'event_data' => array('params' => $p),
			));

			return array('status' => 'success', 'files' => $rebuild_files, 'upload_status' => $upload_files);
		}

		else {
			return $this->Event->error(array(
				'title' => 'Image upload failed',
				'message' => 'DB error',
				'severity' => 'high',
				'event_data' => array('status' => 'error', 'message' => $error_message, 'num_files' => $num_file_total, 'num_uploaded' => $num_file_uploaded, 'files' => $rebuild_files, 'upload_status' => $upload_files),
			));
		}
	}






	/**
	 * Get app primary image
	 * Set primary image if not set
	 * 
	 * Security: None (Backend only)
	 *
	 * @author Robert Andresen <ra@fosenikt.no>
	 *
	 * @param  	Int      $id        App ID
	 * @return  Array               Primary image
	 */
	public function get_primary_image($id)
	{
		$query = "SELECT primary_image FROM apps WHERE id='$id'";
		$result = $this->db->query($query);

		// If merchandise not found, return false
		if ($result->num_rows == 0) {
			return false;
		}
		
		
		// Set primary image if found
		$row = $result->fetch_array();
		
		if (!empty($row['primary_image'])) {
			$primary_image = $row['primary_image'];
		}
		
		// Set first found image as primary if not set
		else {

			// Set first image as primary
			$get_images = $this->get_app_images($id, false);
			if (is_array($get_images) && count($get_images) > 0) {
				error_log('Autoset primary image: ' . $get_images[0]['filename']);
				$this->set_primary_image($id, $get_images[0]['filename']);
			} else {
				error_log('No images for this item');
				return null;
			}

			$primary_image = $get_images[0]['filename'];
		}


		$image_filepath = $this->image_base_path.$id.'/'.$primary_image;
		$image_thumb_filepath = $this->image_base_path.$id.'/_thumbs/'.$primary_image;


		// If primary image does not exist, return "noimage".
		if (!file_exists($image_filepath)) {
			return array('image' => $this->image_none, 'thumb' => $this->image_none);
		}
		



		// Set thumbnail
		// If thumb does not exist, set it to fullsize image
		if (file_exists($image_thumb_filepath)) {
			$thumbnail = $this->image_base_url.$id.'/_thumbs/'.$primary_image;
		} else {
			$thumbnail = $this->image_base_url.$id.'/'.$primary_image;
		}


		// Return primary image
		return array(
			'image' => $this->image_base_url.$id.'/'.$primary_image,
			'thumb' => $thumbnail,
			'filename' => $primary_image,
		);
	}






	/**
	 * Set primary image
	 * 
	 * Security: Check if user has access to app the image is linked to
	 *
	 * @author Robert Andresen <ra@fosenikt.no>
	 *
	 * @param  	Int         $id        App ID
	 * @param  	String      $image     Image
	 * @return  Array                  Event-status
	 */
	public function set_primary_image($id, $image)
	{
		// Check if user has access to app this file is linked to
		if (!$this->chk_app_access($id)) {
			return array('status' => 'error', 'message' => 'Access denied');
		}


		// Query
		$query = "UPDATE apps SET primary_image='$image' WHERE id='$id'";
		$result = $this->db->query($query);


		// If query = OK
		if ($result) {
			$this->Event->add(array(
				'domain' => 'apps',
				'event_type' => 'PrimaryImageSet',
				'entity_id' => $id,
				'entity_tag' => 'merchandise-'.$id,
				'event_data' => array('image' => $image),
			));

			return array('status' => 'success');
		} else {
			return $this->Event->error(array(
				'title' => 'Could not set primary image',
				'message' => 'DB error',
				'severity' => 'high',
				'event_data' => array('id' => $id, 'image' => $image, 'db_error' => $this->db->error),
			));
		}
	}








	/**
	 * Get application images
	 * 
	 * Security: None (backend only)
	 *
	 * @author Robert Andresen <ra@fosenikt.no>
	 *
	 * @param  	Int 		$id	 			  App ID
	 * @param   Boolean 	$get_primary	  Set to false to prevent loop from getting primary image
	 * @return  Array 	    $product_files	  Array with images
	 */
	public function get_app_images($id, $get_primary=true)
	{
		// Declare variable
		$product_files = array();

		// Get primary image
		if ($get_primary) {
			$get_primary_image = $this->get_primary_image($id);
		}


		// Path to images
		$target_base_dir = '/var/www/html/data/apps/'.$id.'/';

		// Get images in path
		$files = glob($target_base_dir.'*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);

		// Loop images if any
		if (is_array($files) && count($files) > 0) {
			foreach($files as $file) {
				
				$this_primary = 0;
				$filename_split = explode('/', $file);
				$exp = explode('.', end($filename_split));
				$ext = strtolower(end($exp));

				// If get primary image, mark as primary
				if (isset($get_primary_image)) {
					if (isset($get_primary_image['filename']) && $get_primary_image['filename'] == end($filename_split)) {
						$this_primary = 1;
					}
				}


				// Path for images and thumbnail
				$image = $this->image_base_url.$id.'/'.end($filename_split);
				$thumbnail = $this->image_base_url.$id.'/_thumbs/'.end($filename_split);

				// Filetypes
				$compatible_files = array('jpg', 'jpeg', 'png', 'gif', 'tiff');

				// If thumbnail does not exist, just return fullsize image,
				// and try to create a thumbnail
				if (!file_exists($target_base_dir.'_thumbs/'.end($filename_split))) {

					// If convertable image, create thumbnail
					if (in_array($ext, $compatible_files)) {
						$this->create_thumbnail($id, end($filename_split));
					}

					// Set fullsize image as thumbnail
					$thumbnail = $image;
				}

				$product_files[] = array(
					'image' => $this->image_base_url.$id.'/'.end($filename_split),
					'thumb' => $thumbnail,
					'filename' => end($filename_split),
					'primary' => $this_primary,
				);
			}
		}

		else {
			$product_files = array();
			return false;
		}

		return $product_files;
	}








	/**
	 * Create thumbnail
	 * This functions just returns true. Check error_log for any errors.
	 *
	 * @todo Better error-handling
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 *
	 * @param  	ID         $app_id       App ID
	 * @param  	String     $filename	 Filename
	 * @return  Boolean                  Just returns true
	 */
	private function create_thumbnail($app_id, $filename)
	{
		
		$ext = '';

		$base_path = $this->image_base_path.$app_id.'/';
		$image_filepath = $base_path.$filename;
		$image_filepath_thumb = $base_path.'_thumbs/'.$filename;

		error_log('Trying to create thumbnail for ' . $image_filepath_thumb);

		$exp = explode('.', $filename);
		$ext = strtolower(end($exp));

		if (empty($ext)) {
			error_log('No fileextention ('.$ext.') for filename "'.$filename.'" -> Returning false');
			return false;
		}

		$compatible_files = array('jpg', 'jpeg', 'png', 'gif', 'tiff');

		if (file_exists($image_filepath) && in_array($ext, $compatible_files)) {
			
			if (file_exists($image_filepath)) {
				

				// Check if thumbnail dir exist
				// Failsafe if not created from before
				if (!is_dir($base_path.'_thumbs/')) {
					error_log('Dir ' . $base_path.'_thumbs/ does not exist');
					if (!mkdir($base_path.'/_thumbs/', 0777, true)) {
						error_log('Could not create thumbnail dir');
					}
				}


				// Try to create thumbnail
				try{
					$thumb = new \Gumlet\ImageResize($image_filepath);
					$thumb->resizeToBestFit(200, 200);
					$thumb->save($base_path.'_thumbs/'.$filename);
				} catch (\Gumlet\ImageResizeException $e) {
					error_log("Something went wrong: " . $e->getMessage());
				}  catch (\Error $e) {
					error_log("Something went wrong: " . $e->getMessage());
				}


				// Try to resize the image
				try{
					$image = new \Gumlet\ImageResize($image_filepath);
					$image->resizeToBestFit(1600, 1200);
					$image->save($image_filepath);
				} catch (\Gumlet\ImageResizeException $e) {
					error_log("Something went wrong: " . $e->getMessage());
				}  catch (\Error $e) {
					error_log("Something went wrong: " . $e->getMessage());
				}
			}
			else {
				error_log('File: ' . $image_filepath . ' does not exist');
			}
		}
		else {
			error_log('File: ' . $image_filepath . ' does not exist or EXT:'.$ext.' is not in array');
		}

		return true;
	}








	/**
	 * Delete an image from disk
	 *
	 * @author Robert Andresen <ra@fosenikt.no>
	 *
	 * @param  	Int       $app_id       App ID
	 * @param  	String    $filename     Filename
	 * @return  Array                   Event-status
	 */
	public function delete_image($app_id, $filename)
	{
		// Check if user has access to app this file is linked to
		if (!$this->chk_app_access($app_id)) {
			return array('status' => 'error', 'message' => 'Access denied');
		}


		// Image dir
		$target_base_dir = $this->image_base_path.'/'.$app_id.'/';

		// Get filename from path
		$split_filename = explode('/', $filename);
		$filename = end($split_filename);


		// Unlink file (aka. delete from disk)
		$delete = unlink($target_base_dir.$filename);


		// Return event-status
		if ($delete) {
			$this->Event->add(array(
				'domain' => 'apps',
				'event_type' => 'ImageDelete',
				'entity_id' => $app_id,
				'entity_tag' => 'app-'.$app_id,
				'event_data' => array('filename' => $filename),
			));

			return array('status' => 'success', 'app_id' => $app_id, 'filename' => $filename);
		} else {
			return $this->Event->error(array(
				'title' => 'Could not delete image',
				'message' => 'File error',
				'severity' => 'high',
				'event_data' => array('status' => 'error', 'app_id' => $app_id, 'filename' => $filename),
			));
		}
	}








	
	/**
	* Check access to app
	* Checks if logged in user is in same company as the app
	*
	* @author Robert Andresen <ra@fosenikt.no>
	*
	* @param  	Int        $app_id	    App ID
	* @return  	Boolean    $var         True = access
	*/
	public function chk_app_access($app_id)
	{
		$query = "SELECT id FROM apps WHERE id='$app_id' AND company_id='{$this->thisUser['customer']['public_id']}'";
		$result = $this->db->query($query);

		if ($result->num_rows > 0) {
			return true;
		}

		return false;
	}

}