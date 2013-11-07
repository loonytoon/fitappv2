<?php

require_once dirname(__FILE__) . '/./AppDotNetPHP/EZAppDotNet.php';

$app = new EZAppDotNet();

// log in user
// if 'Remember me' was checked...
if (isset($_SESSION['rem'])) {
	// pass 1 into setSession in order
	// to set a cookie and session
	$token = $app->setSession(1);
} else {

	// otherwise just set session
	$token = $app->setSession();
}

// redirect user after logging in
header('Location: .');
