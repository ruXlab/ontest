<?php
require_once('OAuth2/Client.php');
// Set his is the URL of this file (http://yourdomain.com/index.php, for example)
const REDIRECT_URI = '';

// The service you want the user to authenticate with
const SERVICE = 'facebook';


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

}
