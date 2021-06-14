<?php

namespace Kapps\Model\Auth\Microsoft;

use \Kapps\Model\General\Db;
use \Kapps\Model\Auth\Microsoft\TokenCache;

class Token extends db {

    /*Properties*/
    protected $accessToken = 'test';
    protected $expires;
    protected $refreshToken;
    //var $resourceOwnerId;
    protected $values=array();



    function __construct()
	{
        $this->User = new \Kapps\Model\Auth\User();
		$this->thisUser = $this->User->get_loggedin_user();

        $this->fetch_token();

        $this->$accessToken = $accessToken;
        $this->$expires = $expires;
        $this->$refreshToken = $refreshToken;
        $this->$values = $values;
	}

    private function set_access_token($accessToken) {
        $this->accessToken = $accessToken;
    }

    private function set_expires($expires) {
        $this->expires = $expires;
    }

    private function set_refresh_token($refresh_token) {
        $this->refreshToken = $refresh_token;
    }

    private function set_values($values) {
        $this->values = $values;
    }



    public function fetch_token() {

        error_log('O365 Request: fetch_token()');

        
        $userID = $this->thisUser['id'];
        
        $query = "SELECT * FROM system_users_o365_keys WHERE user_id='$userID'";
        $db = Db::getInstance();
		$result = $db->query($query);
        if ($result->num_rows == 0) {
            return array('status' => 'error', 'message' => 'No user/access token found');
        }


        // User found
        else {
            // Get user DB data
            while ($obj = $result->fetch_object()) {
                $this->renew_token($obj->expires, $obj->refresh_token);

                $this->set_access_token($obj->access_token);
                $this->set_expires($obj->expires);
                $this->set_refresh_token($obj->refresh_token);
                $this->set_values(array('id_token'=>$obj->id_token));


                $r['accessToken'] = $obj->access_token;
                $r['expires'] = $obj->expires;
                $r['refreshToken'] = $obj->refresh_token;
                $r['values'] = array('id_token'=>$obj->id_token);
            }

            return $r;

        }

    }


    function renew_token($expires, $refresh_token)
    {
        error_log('Trying to renew token');
        $now = time() + 300;
        error_log('Now: ' . $now);
        error_log('Expires: ' . $expires);
        if ($expires <= $now) {

            // Token is expired (or very close to it)
            // so let's refresh

            // Initialize the OAuth client
            $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
                'clientId'                => O365_APP_ID,
                'clientSecret'            => O365_APP_PASSWORD,
                'redirectUri'             => O365_REDIRECT_URI,
                'urlAuthorize'            => O365_AUTHORIZE_ENDPOINT,
                'urlAccessToken'          => O365_TOKEN_ENDPOINT,
                'urlResourceOwnerDetails' => '',
                'scopes'                  => O365_SCOPES
            ]);

            try {
                $newToken = $oauthClient->getAccessToken('refresh_token', [
                    'refresh_token' => $refresh_token
                ]);

                return $newToken->getToken();
            }
            catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                return '';
            }
        }

        else {
            error_log('Token is still valid... Return token here?');
        }
    }



    function get_access_token() {
        return $this->accessToken;
    }

    function get_expires() {
        return $this->expires;
    }

    function get_refresh_token() {
        return $this->refreshToken;
    }

    function get_values() {
        return $this->values;
    }



    public function get_token()
    {
        $r['accessToken'] = $this->get_access_token();
        $r['expires'] = $this->get_expires();
        $r['refreshToken'] = $this->get_refresh_token();
        $r['values'] = $this->get_values();

        $data = (object) $r;

        return $data;
    }
}