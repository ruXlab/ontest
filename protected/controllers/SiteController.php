<?php

class SiteController extends Controller {
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}


	public function beforeAction($action) {
		if (isset(Yii::app()->session['type'])) {
			Yii::app()->singly->setAccessToken(Yii::app()->session['token']);
		}
		return parent::beforeAction($action);
	}
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Redirect to right way
	 */
	public function actionLogin($type) {
		$type = @$_GET['type'];
		if (!$type)
			return $this->redirect(array('site/index'));
		Yii::app()->session['type'] = $type;
		$authUrl = Yii::app()->singly->getAuthServiceUrl($this->getCallbackUrl(), $type);
		$this->redirect($authUrl);
	}

	public function actionCallback() {
		$params = array('code' => @$_GET['code'], 'redirect_uri' => $this->getCallbackUrl());
		print_r($_GET);
		//die('aaaaaaaa');
		if (!$params['code'] || !@Yii::app()->session['type']) {
			unset(Yii::app()->session['type']);
			return $this->redirect(array('site/index'));
		}
		$response = Yii::app()->singly->getAccessToken(Singly::$TOKEN_ENDPOINT, 'authorization_code', $params);
		if ($response['code'] == 200) {
			Yii::app()->session['token'] = $response['result']['access_token'];
	 		print "type: " . Yii::app()->session['type'];

	 		switch (Yii::app()->session['type']) {
 			case 'facebook':
 				return $this->redirect(array('site/facebook'));
 				break;
 			case 'gcontacts':
 				return $this->redirect(array('site/google'));
 				break;
	 		}
		} else {
			unset(Yii::app()->session['type']);
			die('Can not finish auth process');
		}
	}

	public function actionFacebook() {
		if (@Yii::app()->session['type'] != 'facebook') {
			return $this->redirect(array('site/index'));
		}
	    $photos = Yii::app()->singly->fetch('https://api.singly.com/v0/types/photos', array(
	    	'limit' => 10
	    ));
	   	$tmpl = array(
	   		'photos' => array(),
	   		'people' => array(),
	   	);
	   	foreach($photos['result'] as $photo) {
	   		$p = $photo['data']['images'][5];
	   		$p['name'] = @$photo['data']['name'];
	   		$tmpl['photos'][] = $p;
	   	}

	    $people = Yii::app()->singly->fetch('https://api.singly.com/v0/types/contacts', array(
	    	'limit' => 50
	    ));
	    foreach($people['result'] as $someone) {
	    	$s = array(
	    		'name' => @$someone['data']['name'],
	    		'link' => @$someone['data']['link'],
	    		'bio' => @$someone['data']['bio'],
	    		'photo' => @$someone['data']['photo'],
	    		//'s' => $someone['data']
	    	);
	    	$tmpl['people'][] = $s;
	    }

	   	//print_r($tmpl);
	   	$this->render('facebook', $tmpl);
	}

	public function actionGoogle() {
		if (@Yii::app()->session['type'] != 'gcontacts') {
			return $this->redirect(array('site/index'));
		}

	    $profiles = Yii::app()->singly->fetch('https://api.singly.com/v0/profiles/gcontacts', array(
	    	'auth' => true,
	    ));
	    $gToken = ($profiles['result']['auth']['accessToken']);

	    //Yii::app()->singly->client_secret = $gToken;
	    $people = Yii::app()->singly->fetch('https://api.singly.com/v0/services/gcontacts/contacts', array(
	    	'limit' => 50,
	    	'access_token' => $gToken, 
	    ));

	    $contacts = array();

	    foreach($people['result'] as $someone) {
	    	$s = array(
	    		'name' => @$someone['data']['title']['$t'],
	    		'email' => @$someone['data']['gd$email'][0]['address'],
	    		'phone' => @$someone['data']['gd$phoneNumber'][0]['$t'],
	    		'photo' => @$someone['data']['link'][0]['href'],
	    	);
	    	if ($s['photo']) $s['photo'] .= "&access_token={$gToken}"; 
	    	$contacts[] = $s;
	    }
	    print_r($contacts);
	    $this->render('gcontacts', array(
	    	'contacts' => $contacts,
	    ));
	    //print_r($contacts);
	}

	private function getCallbackUrl() {
		return "http://{$_SERVER['HTTP_HOST']}/?r=site/callback";
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
