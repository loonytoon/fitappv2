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
	//$text = htmlspecialchars($text);

	return $text;
}

function verifyAnnoLnk($lnk, $activity_id) {
	$pos = strpos($lnk, "$activity_id");
	//echo strpos($lnk, "$activity_id");
//die("$pos | $lnk | $activity_id");
	if ($pos > 20 && strlen($lnk) != strlen($activity_id)) {
		return $lnk;
	} else {
		return "http://www.runkeeper.com";
	}

}

function getActivitiesToDisplay($totalActivities, $rkCount) {

	//$rkCount = in);

	$rkCount = $totalActivities < $rkCount ? $totalActivities : $rkCount;

	if (key_exists('rkCount', $_GET) && $totalActivities > $rkCount && 5 < $rkCount) {
		return $rkCount;
	} else if ($totalActivities < 5) {
		return $totalActivities;
	} else {
		return 5;
	}

}

function connectToDB() {

	try {
		$dbh = new PDO(DB_CONNECT, DB_USER, DB_PASS);
		return $dbh;
	} catch (PDOException $e) {
		//print "Error!: " . $e -> getMessage() . "<br/>";
		return null;
		//do this more gracefully as we are just tracking posts etc for the most part if the local db fails then it is likely this won't and shouldn't affect the users ability to post
		//die("failed to connect to db");
	}
}

function updateUserInfo($dbh, $rk, $rkp, $app) {
	//adn bits
	if ($dbh) {
		$data = $app -> getUserTokenInfo('me');
		//print_r($data);
		//print_r($app -> getSession());
		$id = $data["user"]["id"];
		$username = $data["user"]["username"];
		$name = $data["user"]["name"];
		$creation = time();
		$atoken = $app -> getSession();

		//print_r($rk);
		$rkid = $rk -> userID;
		//print_r($_SESSION['runkeeperAPIAccessToken']);
		$rtoken = $_SESSION['runkeeperAPIAccessToken'];
		$elite = intval($rkp -> elite);
		// = True?1:0;

		$format = "(%d,'%s','%s','%s','%s','%s',%d,%d)";
		$update_format = "`username` = '%s', `name` = '%s',`atoken` = '%s', `rtoken` = '%s',`elite` = %d";
		$sql = "INSERT INTO `user_tokens`(`id`, `rkid`, `username`, `name`, `atoken`, `rtoken`, `creation`, `elite`) VALUES ";
		$sql .= sprintf($format, $id, $rkid, $username, $name, $atoken, $rtoken, $creation, $elite);
		$sql .= " on duplicate key update ";
		$sql .= sprintf($update_format, $username, $name, $atoken, $rtoken, $elite);
		//die($sql);
		try {
			$dbh -> exec($sql);
		} catch (PDOException $e) {
			//print "Error!: " . $e->getMessage() . "<br/>";
			//die();
			//handle this better
			$dberror = TRUE;
		}
	}
	/*INSERT INTO `user_tokens`(`uid`, `id`, `rkid`, `username`, `name`, `atoken`, `rtoken`, `creation`, `elite`, `live`, `_when`, `_which`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12])8\
	 */
}

function getPostedActivities($items, $dbh) {
	if (count($items) > 0 && $dbh) {
		//print_r($items);
		$activities = array();
		foreach ($items as $value) {
			//print_r($value);
			array_push($activities, str_replace('/fitnessActivities/', '', $value -> uri));
		}

		$or = ' activity = ' . implode(" OR activity = ", $activities);

		$select = 'SELECT activity, post,patter,broadcast FROM posted_activities WHERE ' . $or;

		//die($select);
		$activities = array();
		foreach ($dbh->query($select) as $row) {
			$activities[$row['activity']] = array('post' => $row['post'], 'patter' => $row['patter'], 'broadcast' => $row['broadcast']);
		}
		return $activities;
	} else {
		return array();
	}

}

function verifySettingsForm($post) {
	if (isset($post["postAdn"]) && isset($post["postPatter"])) {
		return 3;
	} else if (isset($post["postPatter"])) {
		return 2;
	} else if (isset($post["postAdn"])) {
		return 1;
	} else {
		return 0;
	}
}

function getRkProfile($rk,$id)
{
	global $cache;
	$profile_read =  $cache->get("profile_read_$id");
	
	if ($profile_read == null)
	{
		$profile_read = $rk -> doRunkeeperRequest('Profile', 'Read');
		 $cache->set("profile_read_$id",$profile_read,300);
	}
	
	return $profile_read;
}

function getRkUserInfo($rk,$id)
{
	global $cache;
	$user_info =  $cache->get("user_info_$id");
	
	if ($user_info == null)
	{
		$user_info = $rk -> doRunkeeperRequest('User', 'Read');
		 $cache->set("user_info_$id",$user_info,300);
	}
	
	return $user_info;
}

function getRkActivities($rk,$id)
{
	global $cache;
	$rkActivities = $cache->get("rkActivities_$id");
	
	if ($rkActivities == null)
	{
		$rkActivities = $rk -> doRunkeeperRequest('FitnessActivityFeed', 'Read');
		$cache->set("rkActivities_$id",$rkActivities,150);
	}
	return $rkActivities;
}


