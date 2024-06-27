<?php
namespace Kapps\Model\Mail;

use Kapps\Model\Database\Db;
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\SMTP;
use \PHPMailer\PHPMailer\Exception;

/**
 * summary
 */
class Send
{
	private $db;

	public function __construct() {
		$this->db = Db::getInstance();
	}


	public function send()
	{
		// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
		if ($stmt = $this->db->prepare('SELECT id, recipient, subject, body FROM mail_out')) {
			// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
			$stmt->execute();
		}

		$result = $stmt->get_result();
		if($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				error_log('Starting to send e-mail with ID: ' . $row['id'].'. Mail: ' . $row['recipient']);

				// Send e-mail with SMTP
				$send = $this->send_mail($row['recipient'], $row['subject'], $row['body']);
				
				// If mail is sent, delete it from database
				if ($send['status'] == 'success') {
					error_log('Mail sent. Deleting e-mail with ID: ' . $row['id']);
					$this->delete_mail($row['id']);
				}
			}

			
		} else {
			//error_log('No emails to send...');
		}

		$stmt->close();
	}



	public function insert_mail($to, $subject, $body)
	{
		$stmt = $this->db->prepare("INSERT INTO mail_out SET recipient=?, subject=?, body=?");
		if ($stmt === false) {
			return array('status' => 'error', 'error' => $this->db->error);
		}

		$result = $stmt->bind_param("sss", $to, $subject, $body);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute();
		$insert_id = $stmt->insert_id;
		if (!$result) $stmt_error = $stmt->error();
		
		$stmt->close();

		if ($result) {
			return array('status' => 'success');
		} else {
			return array('status' => 'error', 'error' => $stmt_error);
		}
	}




	private function delete_mail($id)
	{
		$stmt = $this->db->prepare("DELETE FROM mail_out WHERE id=?");
		if ($stmt === false) {
			error_log('ERROR: Could not delete email with ID: '.$id.'. ErrorID:1');
			return array('status' => 'error', 'error' => $this->db->error);
		}

		$result = $stmt->bind_param("i", $id);

		if ( false===$result ) {
			error_log($stmt->error);
		}

		$result = $stmt->execute();
		$insert_id = $stmt->insert_id;
		if (!$result) $stmt_error = $stmt->error();
		
		$stmt->close();

		if ($result) {
			error_log('Email with ID: ' . $id . ' was deleted');
			return array('status' => 'success');
		} else {
			error_log('ERROR: Could not delete email with ID: '.$id.'. ErrorID:2');
			return array('status' => 'error', 'error' => $stmt_error);
		}
	}


	public function send_mail($recipient, $subject, $body)
	{

		if (empty($recipient)) {
			error_log('ERROR: Cannot send mail. No recipient.');
			return array('status' => 'error', 'message' => 'Empty recipient. No mail sent.');
		}
		elseif (empty($subject)) {
			error_log('ERROR: Cannot send mail. No subject.');
			return array('status' => 'error', 'message' => 'Empty subject. No mail sent.');
		}
		elseif (empty($body)) {
			error_log('ERROR: Cannot send mail. No body.');
			return array('status' => 'error', 'message' => 'Empty body. No mail sent.');
		}


		// Instantiation and passing `true` enables exceptions
		$mail = new \PHPMailer\PHPMailer\PHPMailer(true);

		try {
			//Server settings
			$mail->SMTPDebug = SMTP::DEBUG_SERVER;                   // Enable verbose debug output
			$mail->isSMTP();                                         // Send using SMTP
			$mail->Host       = SMTP_HOST;                           // Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                // Enable SMTP authentication
			$mail->Username   = SMTP_USERNAME;                       // SMTP username
			$mail->Password   = SMTP_PASSWORD;                       // SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
			$mail->Port       = SMTP_PORT;                           // TCP port to connect to

			//Recipients
			$mail->setFrom(SMTP_SENDER_MAIL, SMTP_SENDER_NAME);
			$mail->addAddress($recipient);     // Add a recipient
			$mail->addReplyTo(SMTP_SENDER_MAIL, SMTP_SENDER_NAME);

			// Content
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject = $subject;
			$mail->Body    = $body;
			$mail->AltBody = $body;

			$mail->send();
			return array('status' => 'success');
		} catch (Exception $e) {
			return array('status' => 'error', 'message' => $mail->ErrorInfo);
		}
	}
}