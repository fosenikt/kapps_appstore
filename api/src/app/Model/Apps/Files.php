<?php
namespace Kapps\Model\Apps;

use \Kapps\Model\General\Db;
use \Kapps\Model\General\Event;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Files extends Db
{

	public function __construct()
	{
		$this->Event = new Event();

		$this->AuthUser = new AuthUser;
		$this->thisUser = $this->AuthUser->me();

		$this->file_base_path = $_SERVER['DOCUMENT_ROOT'].'/data/files/';
		$this->file_base_url = '//'.URL.'/data/files/';
	}






	/**
	 * Upload files
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
			return $this->Event->error(array(
				'title' => 'App: No app ID provided for file upload',
				'message' => "Ingen app ID",
				'severity' => 'high',
				'event_data' => array('params' => $p, 'files' => $files),
			));
		}


		// Check if user has access to app this file is linked to
		if (!$this->chk_app_access($p['app_id'])) {
			return array('status' => 'error', 'message' => 'Access denied');
		}


		// Set base dir and URL with APP ID
		$target_base_dir = $this->file_base_path.$p['app_id'].'/'; // Set target dir
		$target_base_url = $this->file_base_url.$p['app_id'].'/'; // Set target dir



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
		$num_file_failed = 0;

		
		// Set new object to upload file
		$upload = new \Kapps\Model\General\Upload;


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



			// Array for allowed filetypes
			// Remember that any changes here, also need to be reflected in backend .htaccess, to allow user to download the actual file
			$ext_img   = array( 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'ico', 'webp' ); //Images
			$ext_file  = array( 'doc', 'docx', 'rtf', 'pdf', 'xls', 'xlsx', 'txt', 'csv', 'html', 'xhtml', 'psd', 'sql', 'log', 'fla', 'xml', 'ade', 'adp', 'mdb', 'accdb', 'ppt', 'pptm', 'pptx', 'odt', 'ots', 'ott', 'odb', 'odg', 'otp', 'otg', 'odf', 'ods', 'odp', 'css', 'ai', 'kmz','dwg', 'dxf', 'hpgl', 'plt', 'spl', 'step', 'stp', 'iges', 'igs', 'sat', 'cgm', 'tiff' ); //Files
			$ext_video = array( 'mov', 'mpeg', 'm4v', 'mp4', 'avi', 'mpg', 'wma', "flv", "webm" ); //Video
			$ext_music = array( 'mp3', 'mpga', 'm4a', 'ac3', 'aiff', 'mid', 'ogg', 'wav' ); //Audio
			$ext_misc  = array( 'zip', 'rar', 'gz', 'tar', 'iso', 'dmg', 'bprelease' ); //Div

			$allowed_filetypes = array_merge($ext_img, $ext_file, $ext_misc, $ext_video, $ext_music); // Merge arrays

			

				


			// Loop files and upload them
			foreach ($rebuild_files as $key => $file) {
				$num_file_total++;

				// Get file extention
				$exp = explode('.', $file["name"]);
				$ext = strtolower(end($exp));


				// Check if extention for filetype is allowed
				if(!in_array($ext, $allowed_filetypes)) {
					$upload_files[$file['name']] = 'Format not allowed';
					$error = true;
					$error_message = "File $ext not allowed";

					return $this->Event->error(array(
						'title' => 'Merchandise: Wrong image format',
						'message' => "Filformat $ext er ikke tillatt",
						'severity' => 'high',
						'event_data' => array('params' => $p, 'files' => $files),
					));

				}
				
				// All OK => Upload Files
				else {
					$upload_files[$file['name']] = $upload->upload_file($target_base_dir, $file, false);

					if ($upload_files[$file['name']]['status'] == 'success') {
						$num_file_uploaded++;
						$path = $target_base_url.$upload_files[$file['name']]['filename'];
						$this->add_file2db($p['app_id'], $file['name'], $file['size'], $ext, $path);
					} else {
						$num_file_failed++;
					}
				}
			}
		}


		// Start returning statuses

		else {
			$error = true;
			$error_message = "No files";

			return $this->Event->error(array(
				'title' => 'App Files: No files to upload',
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
				'title' => 'Merchandise image upload failed',
				'message' => 'DB error',
				'severity' => 'high',
				'event_data' => array('status' => 'error', 'message' => $error_message, 'num_files' => $num_file_total, 'num_uploaded' => $num_file_uploaded, 'files' => $rebuild_files, 'upload_status' => $upload_files),
			));
		}
	}






	/**
	 * Add file to database
	 * 
	 * Really don't know why...
	 * + Easier to query files and info from database than disk
	 * - Harded to maintain filedata in two places
	 * 
	 * Security: Private
	 *
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @todo Fix the close statement, pointless to have it there after function is returned
	 *
	 * @param  	Int       $app_id       App ID
	 * @param  	String    $filename     Filename
	 * @param  	Double    $size         File size
	 * @param  	String    $type         File extenion
	 * @param  	String    $path         URL to file
	 * @return  Array                   Status array
	 */
	private function add_file2db($app_id, $filename, $size, $type, $path)
	{
		// Init DB connection and set charset
		$db = Db::getInstance();
		$db->set_charset("utf8mb4");
		$db->query("SET NAMES 'utf8mb4'");


		// Prepare query
		$stmt = $db->prepare("INSERT INTO files SET app_id=?, filename=?, size=?, type=?, path=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($db->error, E_USER_ERROR);
			return;
		}

		// Bind params to query
		$result = $stmt->bind_param("isiss", $app_id, $filename, $size, $type, $path);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute(); // Execute

		// Return status
		if ($result) {
			return array('status' => 'success', 'file_id' => $stmt->insert_id);
		}
		
		else {
			return array('status' => 'error', 'error' => $stmt->error);
		}

		$stmt->close();
	}






	/**
	 * Delete a file
	 * 
	 * Security: Check if user has access to app the file is linked to
	 *
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @todo Fix the close statement, pointless to have it there after function is returned
	 *
	 * @param  	Int       $app_id       App ID
	 * @param  	String    $file_id      Database file ID
	 * @return  Array                   Status array
	 */
	public function delete($app_id, $file_id)
	{
		// Check if application ID is set
		if (empty($app_id)) {
			return array('status' => 'error', 'message' => 'No app id provided');
		}

		// Check if file ID is set
		if (empty($file_id)) {
			return array('status' => 'error', 'message' => 'No file id provided');
		}

		// Check if user has access to app this file is linked to
		if (!$this->chk_app_access($app_id)) {
			return array('status' => 'error', 'message' => 'Access denied');
		}

		


		// Get filedata
		$get_file = $this->get_file($file_id);
		
		// Check that we got any valid data back
		if (!is_array($get_file) || !isset($get_file['id']) || empty($get_file['id'])) {
			return array('status' => 'error', 'message' => 'Could not get file');
		}

		// Parse out filename from path
		$path = $get_file['path'];
		$path_parts = explode('/', $path);
		$filename = end($path_parts);


		// Set base dir for where to delete the file from
		$target_base_dir = $this->file_base_path.$app_id.'/';


		// Unlink file (aka. delete it from disk)
		$delete = unlink($target_base_dir.$filename);

		// If file was deleted
		if ($delete) {

			// Delete record from database
			$this->delete_file_from_db($file_id);

			// Add event
			$this->Event->add(array(
				'domain' => 'apps',
				'event_type' => 'FileDelete',
				'entity_id' => $file_id,
				'entity_tag' => 'app-'.$app_id,
				'event_data' => array('filename' => $filename),
			));
			
			// Return status
			return array('status' => 'success', 'app_id' => $app_id, 'filename' => $filename);
		}
		
		// If not deleted, return event-error
		else {
			return $this->Event->error(array(
				'title' => 'Could not delete file',
				'message' => 'File error',
				'severity' => 'high',
				'event_data' => array('status' => 'error', 'file_id' => $file_id, 'filename' => $filename),
			));
		}
	}






	/**
	 * Delete file from DB
	 * Remove file record from database
	 * 
	 * Security: Private
	 *
	 * @author Robert Andresen <ra@fosenikt.no>
	 *
	 * @param  	String    $file_id      Database file ID
	 * @return  Array                   Status array
	 */
	private function delete_file_from_db($file_id)
	{
		// Create DB instance
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('DELETE FROM files WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $file_id);
			//$stmt->execute();

			if (!$stmt) {
				return array('status' => 'error', 'message' => $db->error);
			}

			if (!$stmt->execute()) {
				return array('status' => 'error', 'message' => $stmt->error);
			}
		}

		if($stmt->affected_rows == 1) {
			$status = array('status' => 'success');
		} else {
			$status = array('status' => 'error', 'message' => 'Error deleting file', 'msgid' => 2);
		}

		$stmt->close();
		return $status;
	}






	/**
	 * Get file by file ID
	 * 
	 * Security: None (Only backend)
	 *
	 * @author Robert Andresen <ra@fosenikt.no>
	 *
	 * @param  	String    $file_id      File ID
	 * @return  Array     $r            Filedata
	 */
	public function get_file($file_id)
	{
		$r = null;
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('SELECT * FROM files WHERE id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $file_id);
			$stmt->execute();
		}

		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$r = array(
				'id' => $row['id'],
				'app_id' => $row['app_id'],
				'time_uploaded' => $row['time_uploaded'],
				'filename' => $row['filename'],
				'size' => array(
					'bytes' => $row['size'],
					'readable' => $this->humanFileSize($row['size']),
				),
				'type' => $row['type'],
				'path' => $row['path'],
				'icon' => $this->icon($row['type'])
			);
		}
		return $r;
	}






	/**
	 * Get files for app
	 * 
	 * Security: None (Only backend)
	 *
	 * @author Robert Andresen <ra@fosenikt.no>
	 *
	 * @param  	String    $app_id     App ID
	 * @return  Array     $r          Filedata
	 */
	public function get_app_files($app_id)
	{
		$r = null;
		$db = Db::getInstance();

		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $db->prepare('SELECT * FROM files WHERE app_id=?')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->bind_param('i', $app_id);
			$stmt->execute();
		}

		$result = $stmt->get_result();
		while ($row = $result->fetch_assoc()) {
			$r[] = array(
				'id' => $row['id'],
				'time_uploaded' => $row['time_uploaded'],
				'filename' => $row['filename'],
				'size' => array(
					'bytes' => $row['size'],
					'readable' => $this->humanFileSize($row['size']),
				),
				'type' => $row['type'],
				'path' => $row['path'],
				'icon' => $this->icon($row['type'])
			);
		}
		return $r;
	}






	/**
	 * Get font-awsome icon for fileextention
	 * 
	 * Security: Private
	 *
	 * @see https://fontawesome.com/
	 * @author Robert Andresen <ra@fosenikt.no>
	 *
	 * @param  	String    $t     Fileextention
	 * @return  String           Icon
	 */
	private function icon($t) {

			if ($t == 'pptm' || $t == 'ppt' || $t == 'pptx') return '<i class="fal fa-fw fa-file-powerpoint"></i>';
		elseif ($t == 'jpg' || $t == 'jpeg' || $t == 'png' || $t == 'bmp' || $t == 'svg' || $t == 'ico' || $t == 'webp' || $t == 'tiff')  return '<i class="fal fa-fw fa-file-image"></i>';
		elseif ($t == 'doc' || $t == 'docx' || $t == 'rtf')  return '<i class="fal fa-fw fa-word"></i>';
		elseif ($t == 'pdf')  return '<i class="fal fa-fw fa-file-pdf"></i>';
		elseif ($t == 'xls' || $t == 'xlsx')  return '<i class="fal fa-fw fa-file-excel"></i>';
		elseif ($t == 'csv')  return '<i class="fal fa-fw fa-file-csv"></i>';
		elseif ($t == 'txt')  return '<i class="fal fa-fw fa-file-alt"></i>';
		elseif ($t == 'html' || $t == 'xhtml' || $t == 'css')  return '<i class="fal fa-fw fa-file-code"></i>';
		elseif ($t == 'psd')    return '<i class="fal fa-fw fa-image"></i>';
		elseif ($t == 'sql')    return '<i class="fal fa-fw fa-file-code"></i>';
		elseif ($t == 'log')    return '<i class="fal fa-fw fa-file-alt"></i>';
		elseif ($t == 'fla')    return '<i class="fal fa-fw fa-transporter-2"></i>'; // Adobe Animate
		elseif ($t == 'xml')    return '<i class="fal fa-fw fa-file-code"></i>';
		elseif ($t == 'ade')    return '<i class="fal fa-fw fa-database"></i>'; // Microsoft Access
		elseif ($t == 'adp')    return '<i class="fal fa-fw fa-database"></i>'; // Microsoft Access
		elseif ($t == 'mdb')    return '<i class="fal fa-fw fa-database"></i>'; // Microsoft Access
		elseif ($t == 'accdb')  return '<i class="fal fa-fw fa-database"></i>'; // Microsoft Access 2007
		elseif ($t == 'odt')    return '<i class="fal fa-fw fa-file-alt"></i>'; // Word ala. Apache OpenOffice and LibreOffice
		elseif ($t == 'ott')    return '<i class="fal fa-fw fa-file-alt"></i>'; // Word ala. Apache OpenOffice and LibreOffice
		elseif ($t == 'ots')    return '<i class="fal fa-fw fa-file-spreadsheet"></i>'; // Spreadsheet
		elseif ($t == 'ods')    return '<i class="fal fa-fw fa-file-spreadsheet"></i>'; // Spreadsheet
		elseif ($t == 'odb')    return '<i class="fal fa-fw fa-database"></i>'; // Apache OpenOffice Base
		elseif ($t == 'odg')    return '<i class="fal fa-fw fa-file-edit"></i>'; // Apache Draw
		elseif ($t == 'otg')    return '<i class="fal fa-fw fa-file-edit"></i>'; // Drawing template
		elseif ($t == 'odf')    return '<i class="fal fa-fw fa-function"></i>'; // Apache OpenOffice Math
		elseif ($t == 'otp')    return '<i class="fal fa-fw fa-presentation"></i>'; // Presentation
		elseif ($t == 'odp')    return '<i class="fal fa-fw fa-presentation"></i>'; // Presentation
		elseif ($t == 'ai')     return '<i class="fal fa-fw fa-vector-square"></i>'; // Adobe vector based graphics
		elseif ($t == 'kmz')    return '<i class="fal fa-fw fa-file"></i>'; // Keyhole Markup language
		elseif ($t == 'dwg')    return '<i class="fal fa-fw fa-cube"></i>'; // AutoCAD
		elseif ($t == 'dxf')    return '<i class="fal fa-fw fa-cube"></i>'; // AutoCAD
		elseif ($t == 'hpgl')   return '<i class="fal fa-fw fa-print"></i>'; // HP Graphics Language file that sends printing instructions to plotter printers
		elseif ($t == 'plt')    return '<i class="fal fa-fw fa-cube"></i>'; // AutoCAD
		elseif ($t == 'spl')    return '<i class="fal fa-fw fa-file"></i>'; // schematic diagram created by sPlan
		elseif ($t == 'step')   return '<i class="fal fa-fw fa-file-cube"></i>'; // 3D model file formatted in STEP (Standard for the Exchange of Product Data)
		elseif ($t == 'stp')    return '<i class="fal fa-fw fa-file-cube"></i>'; // 3D assembly file supported by various mechanical design programs, such as IMSI TurboCAD and Autodesk Fusion 360
		elseif ($t == 'iges')   return '<i class="fal fa-fw fa-file-cube"></i>'; // exchange 2D or 3D design information between CAD programs, such as Autodesk AutoCAD and ACD...
		elseif ($t == 'igs')    return '<i class="fal fa-fw fa-file-cube"></i>'; // graphics file saved in a 2D/3D vector format based on the Initial Graphics Exchange Specification (IGES)
		elseif ($t == 'sat')    return '<i class="fal fa-fw fa-file-cube"></i>'; // 3D model saved in Spatial's ACIS solid modeling format
		elseif ($t == 'cgm')    return '<i class="fal fa-fw fad-file-vector-square"></i>'; // image saved in a vector graphics format
		elseif ($t == 'mov' || $t == 'mpeg' || $t == 'm4v' || $t == 'mp4' || $t == 'avi' || $t == 'mpg' || $t == 'wma' || $t == 'flv' || $t == 'webm')  return '<i class="fal fa-fw fa-file-video"></i>';
		elseif ($t == 'mp3' || $t == 'mpga' || $t == 'm4a' || $t == 'ac3' || $t == 'aiff' || $t == 'mid' || $t == 'ogg' || $t == 'wav')  return '<i class="fal fa-fw fa-audio"></i>';
		elseif ($t == 'zip' || $t == 'rar' || $t == 'gz' || $t == 'tar')  return '<i class="fal fa-fw fa-file-archive"></i>';
		elseif ($t == 'iso' || $t == 'dmg')  return '<i class="fal fa-fw fa-compact-disc"></i>';
		elseif ($t == 'bprelease')  return '<i class="fad fa-fw fa-user-robot"></i>';

		else return '<i class="fad fa-fw fa-file"></i>';
	}






	/**
	 * Convert filesize bytes to a human readable filesize
	 * Random function from stackoverflow
	 * 
	 * Security: Private
	 *
	 * @param  	String    $t     Fileextention
	 * @return  String           Icon
	 */
	private function humanFileSize($size,$unit="") {
		if( (!$unit && $size >= 1<<30) || $unit == "GB")
			return number_format($size/(1<<30),2)."GB";
		if( (!$unit && $size >= 1<<20) || $unit == "MB")
			return number_format($size/(1<<20),2)."MB";
		if( (!$unit && $size >= 1<<10) || $unit == "KB")
			return number_format($size/(1<<10),2)."KB";
		
		return number_format($size)." bytes";
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
		$db = Db::getInstance();
		$result = $db->query($query);

		if ($result->num_rows > 0) {
			return true;
		}

		return false;
	}

}