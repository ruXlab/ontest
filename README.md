

Example of using Singly with Yii framework
======

The application provides access to the login through several services:

*	Login via facebook

	Get 10 photos and 50 friends from profile

*	Login via google

	Get 50 contacts

### Installation


* Clone this repo: `git clone git@github.com:ruxman/ontest.git`
* Download and unpack latest version of [Yii framework](http://yiiframework.com/)
* Create file protected/config/env.php
		<?php

		return array(
		    'components' => array( 
		    	'singly' => array(
					'client_id' => 'YOUR_CLIENT_ID_FROM_SINGLY',
					'client_secret' => 'YOUR_SINGLY_KEY_FOR_APP_FROM_SINGLY',
		    	),
		    ),
		);
	
* Enjoy Singly API :)
