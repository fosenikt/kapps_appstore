<?php
namespace Kapps\Model\Users;

/**
 * summary
 */
class Utils
{
	public static function displayname($firstname, $lastname, $mail)
	{
		$displayname = '';
		if (!empty($firstname) && !empty($lastname)) {
			$displayname = "$firstname $lastname";
		}

		elseif (!empty($firstname) && empty($lastname)) {
			$displayname = "$firstname";
		}

		elseif (empty($firstname) && !empty($lastname)) {
			$displayname = "$lastname";
		}

		elseif (!empty($mail)) {
			$displayname = "$mail";
		}

		else {
			$displayname = 'Ukjent';
		}


		return $displayname;
	}




	/**
	 * Create initials
	 * 
	 * Security: None (backend only)
	 * 
	 * @author Robert Andresen <ra@fosenikt.no>
	 * 
	 * @param 	String      $firstname    Firstname
	 * @param 	String      $lastname     Lastname
	 * @param 	String      $mail         Mail
	 * @return 	String                    Initials
	 */
	public static function initials($firstname, $lastname, $mail)
	{
		if (!empty($firstname) && !empty($lastname)) {
			$f = substr($firstname, 0, 1);
			$l = substr($lastname, 0, 1);
			return strtoupper($f.$l);
		}

		if (!empty($firstname) && empty($lastname)) {
			$f = substr($firstname, 0, 2);
			return strtoupper($f);
		}

		if (empty($firstname) && !empty($lastname)) {
			$l = substr($lastname, 0, 2);
			return strtoupper($l);
		}


		if (!empty($mail)) {
			list($user, $domain) = explode('@', $mail);

			$split_username = explode('.', $user);

			if (is_array($split_username) && count($split_username) >= 2) {
				$f = substr($split_username[0], 0, 1);
				$l = substr(end($split_username), 0, 1);
				return strtoupper($f.$l);
			}

			else {
				return strtoupper(substr($mail, 0, 2));
			}
		}

		return 'XX';
	}

}