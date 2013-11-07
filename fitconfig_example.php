<?php
if (!$INCLUDE_HASH || $INCLUDE_HASH != "c43a1883e171eebc0133785753745816")
{
	header("HTTP/1.1 404 NOT FOUND");
	die;
}

define('DIR', dirname(__FILE__) . '/');

define('APPDOT_PATH', 	DIR.'AppDotNetPHP/');
define('RK_PATH',     	DIR.'runkeeper/');
define('FA_PATH',		DIR.'fitinc/');

define('RK_API_YML', DIR . 'runkeeper/config/rk-api.yml');

define('HASHTAG', 'addFitnessChallenge');
define('RK', 'See this on RunKeeper');
define('UFO', '<=>');

//define('P_CHANNEL', 23835);//adn
define('P_CHANNEL', 25001);//add

define('Title', 'Fit');

define('rorrim', 'l');
define('a_host', 'xxxx');

///stuff for posting

$distArray['distance_unit'] = "km";
$distArray['distance_convert'] = 0.001;
$distArray['other_unit'] = 'mi';
$distArray['other_convert'] = $distArray['distance_convert'] * 0.621371192;
?>