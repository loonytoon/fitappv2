<?php

/*print_r($_POST);
print_r($_GET);
*/
$INCLUDE_HASH = "c43a1883e171eebc0133785753745816";
require_once $_SERVER['DOCUMENT_ROOT'] . '/../fitappv2/' . 'fitconfig.php';

//verify posted fields
$pAllowed = array();
$pAllowed[] = 'message';
$pAllowed[] = 'annolnk';
$pAllowed[] = 'postTo';
$pSent = array_keys($_POST);

$gAllowed = array();
$gAllowed[] = 'rkCount';
$gAllowed[] = 'activity_id';

$gSent = array_keys($_GET);

sort($pAllowed);
sort($pSent);
sort($gAllowed);
sort($gSent);

$activity_id = intval($_GET['activity_id']);

if ($pAllowed == $pSent && $gAllowed == $gSent &&  $activity_id > 0) {
	//echo "all good";
	include FA_PATH.'postMethods.php';
	include FA_PATH.'postLogic.php';
}
else {
	
	//do some error handling
}
?>