<?php
if (!$INCLUDE_HASH || $INCLUDE_HASH != "c43a1883e171eebc0133785753745816") {
	header("HTTP/1.1 404 NOT FOUND");
	die ;
}
function unix2human($unix) {

	//--------------------------------------------------
	// Maths

	$sec = $unix % 60;
	$unix -= $sec;

	$minSeconds = $unix % 3600;
	$unix -= $minSeconds;
	$min = ($minSeconds / 60);

	$hourSeconds = $unix % 86400;
	$unix -= $hourSeconds;
	$hour = ($hourSeconds / 3600);

	$daySeconds = $unix % 604800;
	$unix -= $daySeconds;
	$day = ($daySeconds / 86400);

	$week = ($unix / 604800);

	//--------------------------------------------------
	// Text

	$output = '';

	if ($week > 0)
		$output .= ', ' . $week . ' week' . ($week != 1 ? 's' : '');
	if ($day > 0)
		$output .= ', ' . $day . ' day' . ($day != 1 ? 's' : '');
	if ($hour > 0)
		$output .= ', ' . $hour . ' hour' . ($hour != 1 ? 's' : '');
	if ($min > 0)
		$output .= ', ' . $min . ' minute' . ($min != 1 ? 's' : '');

	if ($sec > 0 || $output == '') {
		$output .= ', ' . $sec . ' second' . ($sec != 1 ? 's' : '');
	}

	//--------------------------------------------------
	// Grammar

	$output = substr($output, 2);
	$output = preg_replace('/, ([^,]+)$/', ' and $1', $output);

	//--------------------------------------------------
	// Return the output

	return $output;

}

function isADNAuthed($app) {
	if ($app -> getSession()) {
		try {
			$denied = $app -> getUser();
			return true;
		} catch (AppDotNetException $e) {// catch revoked access and existing session // Safari 6 doesn't like
			if ($e -> getCode() == 401) {
				print " success (could not get access)\n";
			} else {
				throw $e;
			}
			$app -> deleteSession();
			return false;
		}
	} else {
		return false;
	}

}

function isRKAuthed() {
	return key_exists('runkeeperAPIAccessToken', $_SESSION);
}

function verifyPostText($text) {
	//is it the current length?
	$text = trim($text);
	if (strlen($text) < 1 && strlen($text) > 256) {
		return false;
	}

	//its the corrent length so lets remove html and other fruityness
	$text = strip_tags($text);
	//is this step needed?
	$text = htmlspecialchars($text);

	return $text;
}

function verifyAnnoLnk($lnk,$activity_id)
{
	$pos = strpos($lnk, $activity_id);

	if ($pos > 20 && strlen($lnk) != strlen($activity_id))
	{
		return $lnk;
	}
	else
	{
		return "http://www.runkeeper.com";	
	}
	
}

function getActivitiesToDisplay($totalActivities,$rkCount) {

	//$rkCount = in);
	
	$rkCount = $totalActivities<$rkCount?$totalActivities:$rkCount;
	
	if (key_exists('rkCount', $_GET) && $totalActivities > $rkCount && 5 < $rkCount) {
		return  $rkCount;
	} else if($totalActivities < 5) {
		return $totalActivities;
	}else{
		return 5;
	}

}
?>