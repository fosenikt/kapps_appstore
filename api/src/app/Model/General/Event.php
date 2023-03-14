<?php
namespace Kapps\Model\General;

use \Kapps\Model\General\Db;
use \Kapps\Model\Auth\User as AuthUser;

/**
 * summary
 */
class Event extends db
{

	private $AuthUser;
	private $thisUser;

	public function __construct()
	{
		$this->AuthUser = new AuthUser;
		$this->thisUser = $this->AuthUser->me();
	}


	public function add($p)
	{

		if (isset($p['company_id'])) {
			$company_id = $p['company_id'];
		} else {
			$company_id = $this->thisUser['customer']['public_id'];
		}

		if (isset($p['user_id'])) {
			$user_id = $p['user_id'];
		} else {
			$user_id = $this->thisUser['id'];
		}

		if (isset($p['domain'])) {
			$domain = $p['domain'];
		} else {
			$domain = '';
		}

		if (isset($p['event_type'])) {
			$event_type = $p['event_type'];
		} else {
			$event_type = '';
		}

		if (isset($p['entity_id'])) {
			$entity_id = $p['entity_id'];
		} else {
			$entity_id = '';
		}

		if (isset($p['entity_tag'])) {
			$entity_tag = $p['entity_tag'];
		} else {
			$entity_tag = '';
		}

		if (isset($p['event_data'])) {
			$event_data = json_encode($p['event_data'], JSON_UNESCAPED_UNICODE);
		} else {
			$event_data = '';
		}

		$db = Db::getInstance();



		$db->set_charset("utf8mb4");
		$db->query("SET NAMES 'utf8mb4'");

		$stmt = $db->prepare("INSERT INTO event SET 
				company_id=?, user_id=?, domain=?, event_type=?, entity_id=?, entity_tag=?, event_data=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($db->error, E_USER_ERROR);
			return;
		}

		$result = $stmt->bind_param("iississ", $company_id, $user_id, $domain, $event_type, $entity_id, $entity_tag, $event_data);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute();

		if ($result) {
			//error_log('Event logged');
		} else {
			error_log('Error while logging event');
			error_log($stmt->error);
		}

		$stmt->close();
	}




	public function error($p)
	{

		if (isset($p['department_id'])) {
			$department_id = $p['department_id'];
		} else {
			$department_id = $this->get_my_department();
		}

		if (isset($p['user_id'])) {
			$user_id = $p['user_id'];
		} else {
			$user_id = $this->thisUser['id'];
		}

		/* if (isset($p['domain'])) {
			$domain = $p['domain'];
		} else {
			$domain = get_parent_class($this);
		} */

		$trace = debug_backtrace();
		if (isset($trace[1])) {
			// $trace[0] is ourself
			// $trace[1] is our caller
			// and so on...
			//var_dump($trace[1]);

			$domain = "{$trace[1]['class']} :: {$trace[1]['function']}";

		}

		if (isset($p['title'])) {
			$title = $p['title'];
		} else {
			$title = '';
		}

		if (isset($p['message'])) {
			$message = $p['message'];
		} else {
			$message = '';
		}

		if (isset($p['severity'])) {
			$severity = $p['severity'];
		} else {
			$severity = 'low';
		}

		if (isset($p['error_id'])) {
			$error_id = $p['error_id'];
		} else {
			$error_id = 0;
		}

		if (isset($p['entity_id'])) {
			$entity_tag = $p['entity_id'];
		} else {
			$entity_tag = 0;
		}

		if (isset($p['event_data'])) {
			$event_data = json_encode($p['event_data'], JSON_UNESCAPED_UNICODE);
		} else {
			$event_data = '';
		}

		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$ip_address = $_SERVER['REMOTE_ADDR'];


		$db = Db::getInstance();



		$db->set_charset("utf8mb4");
		$db->query("SET NAMES 'utf8mb4'");

		$stmt = $db->prepare("INSERT INTO log_errors SET 
				user_agent=?, ip_address=?, user_id=?, department_id=?, domain=?, title=?, message=?, severity=?, error_id=?, entity_id=?, event_data=?");
		if ($stmt === false) {
			error_log('Statement false');
			trigger_error($db->error, E_USER_ERROR);
			return;
		}

		$result = $stmt->bind_param("ssiisssssis", $user_agent, $ip_address, $user_id, $department_id, $domain, $title, $message, $severity, $error_id, $entity_id, $event_data);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute();

		if ($result) {
			error_log('Error logged');
		} else {
			error_log('Error while logging error');
			error_log($stmt->error);
		}

		$stmt->close();

		return array('status' => 'error', 'title' => $title, 'message' => $message, 'error_id' => $error_id, 'msgid' => $error_id); // msgid for backwards-compability in frontend
	}





	private function get_my_department()
	{
		$query = "SELECT T.department_id FROM hd_teams_members AS M
				  INNER JOIN hd_teams AS T ON T.id = M.team_id
				  WHERE M.user_id='{$this->thisUser['id']}'";
		$db = Db::getInstance();
		$result = $db->query($query);

		if ($result && $result->num_rows > 0) {
			$row = $result->fetch_array();
			return $row['department_id'];
		}

		return 0;
	}

}