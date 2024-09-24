<?php

/**
 * getToken.php
 * 
 * This file will fetch the token and auth the user with data
 * from the O365 login
 * 
 * @author     Robert Andresen <ra@fosenikt.no>
 * @copyright  2020 Fosen IKT
 * @license    MIT
 */

require_once('../../config.php');
require_once('../../app/autoload.php');
require_once('../../vendor/autoload.php');



/**
 * Get O365 token
 * 
 * Response:
 * ----------------------------------------------
 * League\OAuth2\Client\Token\AccessToken Object
 * (
 *     [accessToken:protected] => eyJ0e...
 *     [expires:protected] => 1613639792
 *     [refreshToken:protected] => 0.ASAAA_sl5-QCNUKv3jxKVPDLWWJYEaNGC1NKl...
 *     [resourceOwnerId:protected] => 
 *     [values:protected] => Array
 *         (
 *             [token_type] => Bearer
 *             [scope] => Calendars.Read openid profile User.Read email
 *             [ext_expires_in] => 3599
 *             [id_token] => eyJ0eXAiOiJKV1Qm5Pbz...
 *         )
 * 
 * )
 */
$authController = new Kapps\Model\Auth\Microsoft\AuthController(); // Used to get O365 token
$getToken = $authController->gettoken();

$json_tokens = json_decode(json_encode($getToken), true);
$array = (array)$getToken;




/**
 * Get user profile-data
 * 
 * $profile => array(
 *	  @odata.context (string)
 *	  businessPhones (Array [0]=Phone)
 *	  displayName (String)
 *	  givenName (String)
 *	  jobTitle (String)
 *	  mail (String)
 *	  mobilePhone (String)
 *	  officeLocation (String)
 *	  preferredLanguage (String)
 *	  surname (String)
 *	  userPrincipalName (String)
 *	  id (String)
 *)
 * 
 */
$outlook = new Kapps\Model\Auth\Microsoft\OutlookController; // Used to get profile
$profile = $outlook->me();





/**
 * Get users profile photo
 */
$photo = new Kapps\Model\Auth\Microsoft\Photo; // Used to get users profile picture
$get_photo = $photo->fetch_photo($profile['userPrincipalName']);





/**
 * Validate login locally and create a local JWT token
 */
$auth = new Kapps\Model\Auth\O365Auth; // Used to create a local token
$token = $auth->auth_o365_user($profile, $json_tokens);




/**
 * Redirect user back to login-page for local auth and redirect into app
 */
if (isset($_SESSION['redir']) && !empty($_SESSION['redir'])) {
	$redir = $_SESSION['redir'];
} else {
	$redir = '';
}

if (!empty($token)) {
	header('Location: https://'.FRONTEND_HOST.'/user/login?login=success&token='.$token.'&autologin=0&redir='.urlencode($redir));
} else {
	header('Location: https://'.FRONTEND_HOST.'/user/login?login=error&error=1&redir='.urlencode($redir));
}