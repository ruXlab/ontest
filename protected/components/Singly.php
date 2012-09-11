<?php
require_once('OAuth2/Client.php');
require_once('OAuth2/GrantType/IGrantType.php');
require_once('OAuth2/GrantType/AuthorizationCode.php');


class Singly extends OAuth2\Client {
	public static $AUTHORIZATION_ENDPOINT = 'https://api.singly.com/oauth/authorize';
	public static $TOKEN_ENDPOINT = 'https://api.singly.com/oauth/access_token';

	public function __construct() {

	}

	public function init() {

	}

	public function setClientId($client_id) {
		
		$this->client_id = $client_id;
	}

	public function setClientSecret($client_secret) {
		$this->client_secret = $client_secret;
	}

	public function getAuthServiceUrl($redirect, $service) {
		return $this->getAuthenticationUrl(self::$AUTHORIZATION_ENDPOINT, $redirect) 
				. "&service={$service}";				
	} 
}
