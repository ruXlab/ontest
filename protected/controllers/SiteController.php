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
	 * Displays the login page
	 */
	public function actionLogin($type = 'facebook') {
		Yii::app()->session['type'] = $type;
		$authUrl = Yii::app()->singly->getAuthServiceUrl($this->getCallbackUrl(), $type);
		$this->redirect($authUrl);
	}

	public function actionCallback() {
		$params = array('code' => @$_GET['code'], 'redirect_uri' => $this->getCallbackUrl());
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
 			case 'google':
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

	   	print_r($tmpl);
	   	$this->render('facebook', $tmpl);
	}

	public function actionGoogle() {
		if (@Yii::app()->session['type'] != 'google') {
			return $this->redirect(array('site/index'));
		}
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
