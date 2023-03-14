<?php

namespace Kapps\Model\Auth\Microsoft;

use Kapps\Model\Auth\Microsoft\TokenCache;

class AuthController
{
	
	public function signin() {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

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

		$authorizationUrl = $oauthClient->getAuthorizationUrl();

		// Save client state so we can validate in response
  		$_SESSION['oauth_state'] = $oauthClient->getState();

		return $authorizationUrl;
	}



	public function gettoken() {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		error_log('O365 Request: gettoken()');

		// Authorization code should be in the "code" query param
		if (isset($_GET['code'])) {
			// Check that state matches
			if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth_state'])) {
				exit('State provided in redirect does not match expected value.');
			}

			// Clear saved state
			unset($_SESSION['oauth_state']);

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
				// Make the token request
				$accessToken = $oauthClient->getAccessToken('authorization_code', [
					'code' => $_GET['code']
				]);

				// Save the access token and refresh tokens in session
				// This is for demo purposes only. A better method would
				// be to store the refresh token in a secured database
				$tokenCache = new TokenCache;
				$tokenCache->storeTokens($accessToken->getToken(), $accessToken->getRefreshToken(),
				$accessToken->getExpires());

                // Rebuild events and convert to array
                foreach ($accessToken as $key => $value) {
                    $tokens[] = $this->getProtectedValue($value);
                }

				// Redirect back to mail page
				return $accessToken;
				//return $tokens;
			}
			catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
				exit('ERROR getting tokens: '.$e->getMessage());
			}
			exit();
		}
		elseif (isset($_GET['error'])) {
			exit('ERROR: '.$_GET['error'].' - '.$_GET['error_description']);
		}
	}



    function getProtectedValue($obj) {
		$array = (array)$obj;
		$first_key = key($array);
		return $array[$first_key];
	}
}