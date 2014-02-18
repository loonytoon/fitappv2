<?php

if (!$INCLUDE_HASH || $INCLUDE_HASH != "c43a1883e171eebc0133785753745816")
{
	header("HTTP/1.1 404 NOT FOUND");
	die;
}

$INCLUDE_HASH = $INCLUDE_HASH;
require_once APPDOT_PATH . 'EZAppDotNet.php';

require_once FA_PATH . 'commons.php';
require_once FA_PATH . 'templates.php';

require_once RK_PATH . 'vendor/autoload.php';
require_once RK_PATH . 'lib/runkeeperAPI.class.php';

$app = new EZAppDotNet();
$url = $app -> getAuthUrl();
$dberror = false;

if (isADNAuthed($app)) 
{

	$rk = new runkeeperAPI(RK_API_YML);
}
?>

<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en" >
	<!--<![endif]-->

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<title><?php echo Title;?></title>
		<link rel="stylesheet" href="stylesheets/app.css">
	</head>
	<body>

		