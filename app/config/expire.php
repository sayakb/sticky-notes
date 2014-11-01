<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Paste expiration options
	|--------------------------------------------------------------------------
	|
	| This file defines the various expiration times for a paste.
	|
	| The first entry in the values defines the language key, and the second
	| entry defines the criteria of consideration of the expire entry.
	|
	*/

	'1800'      => array('expire_30mins', TRUE),

	'21600'     => array('expire_6hrs', TRUE),

	'86400'     => array('expire_1day', TRUE),

	'604800'    => array('expire_1week', TRUE),

	'2592000'   => array('expire_1month', TRUE),

	'31536000'  => array('expire_1year', TRUE),

	'0'         => array('expire_forever', Paste::noExpire()),

);
