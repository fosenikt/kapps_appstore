<?php

namespace Kapps\Model\Auth\Microsoft;

class TokenCache {
  public function storeTokens($access_token, $refresh_token, $expires) {
    $_SESSION['access_token'] = $access_token;
    $_SESSION['refresh_token'] = $refresh_token;
    $_SESSION['token_expires'] = $expires;
  }

  public function clearTokens() {
    unset($_SESSION['access_token']);
    unset($_SESSION['refresh_token']);
    unset($_SESSION['token_expires']);
  }

  public function getAccessToken() {
    // Check if tokens exist
    if (empty($_SESSION['access_token']) ||
        empty($_SESSION['refresh_token']) ||
        empty($_SESSION['token_expires'])) {
      return '';
    }

    // Check if token is expired
    //Get current time + 5 minutes (to allow for time differences)
    $now = time() + 300;
    if ($_SESSION['token_expires'] <= $now) {
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
          'refresh_token' => $_SESSION['refresh_token']
        ]);

        // Store the new values
        $this->storeTokens($newToken->getToken(), $newToken->getRefreshToken(),
          $newToken->getExpires());

        return $newToken->getToken();
      }
      catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        return '';
      }
    }
    else {
      // Token is still valid, just return it
      return $_SESSION['access_token'];
    }
  }
}