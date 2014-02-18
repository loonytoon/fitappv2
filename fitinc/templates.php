<?php
if (!$INCLUDE_HASH || $INCLUDE_HASH != "c43a1883e171eebc0133785753745816") {
	header("HTTP/1.1 404 NOT FOUND");
	die ;
}

function template_signedOutHeader() {
	include "templates/globalheaderstart.php";
	include "templates/signedoutheader.php";
	include "templates/globalheaderend.php";
}

function template_adnSignedInHeader($app) {

	$data = $app -> getUserTokenInfo('me');

	include "templates/globalheaderstart.php";
	include "templates/adnSignedInHeader.php";
	include "templates/globalheaderend.php";

}

function template_signedInHeader($app, $profile_read) {

	$data = $app -> getUserTokenInfo('me');

	include "templates/globalheaderstart.php";
	include "templates/signedInHeader.php";
	include "templates/globalheaderend.php";
}

function template_adnLoginButton($url) {
	include "templates/adnsignin.php";
}

function template_rkLoginButton($rk) {
	include "templates/rksignin.php";
}

function template_activitiesStart() {
	echo '<section class="row">';
}

function tempate_activitiesDisplay($rkActivities, $user_info, $settings_read, $profile_read, $count, $openActivity, $dbh) {
	$distance_unit = "km";
	$distance_convert = 0.001;
	
	//print_r ($settings_read);
	
	if ($settings_read -> distance_units != $distance_unit) {
		$other_unit = $distance_unit;
		$other_convert = $distance_convert;
		$distance_unit = 'mi';
		$distance_convert *= 0.621371192;
	} else {
		$other_unit = 'mi';
		$other_convert = $distance_convert * 0.621371192;
	}

	$posted_array = getPostedActivities($rkActivities -> items, $dbh);
	//array("263766794"=>array("post"=>1,"patter"=>1,"broadcast"=>1")//get from db

	for ($i = 0; $i < $count; $i++) {
		$value = $rkActivities -> items[$i];
		//print_r($value);
		//die;
		$da = new DateTime($value -> start_time);
		$activity_id = str_ireplace("/fitnessActivities/", "", $value -> uri);
		if ($openActivity == $activity_id) {
			$openStyle = 'style="display:block;"';
			$hideButton = 'style="display:none;"';
		} else {
			$openStyle = "";
			$hideButton = "";
		}
		if (key_exists($activity_id, $posted_array)) {
			//change button to say it is posted
			$postToButtonText = "Activity posted";
			$postToButtonClass = " secondary";
			//$postBtnClass
			if ($posted_array[$activity_id]['post'] == 1) {
				$postBtnClass = " secondary strike-through";
			} else {
				$postBtnClass = "";
			}
			if ($posted_array[$activity_id]['patter'] == 1) {
				$patterBtnClass = " secondary strike-through";
			} else {
				$patterBtnClass = "";
			}
			if ($posted_array[$activity_id]['broadcast'] == 1) {
				$broadcastBtnClass = " secondary strike-through";
			} else {
				$broadcastBtnClass = "";
			}
		} else {
			$postToButtonText = "Post to adn";
			$postToButtonClass = "";
			$postBtnClass = "";
			$patterBtnClass = "";
			$broadcastBtnClass = "";
		}
		include "templates/activity.php";
	}

}

function template_activitiesEnd($count, $openActivity) {

	include_once "templates/activitiesEndTemplate.php";
}

function template_settingsForm($dbh, $rk, $app, $live) {
	//$live = 0;
	$postChecked = "";
	$patterChecked = "";
	$autoPostStatus = "There was a database problem and auto post settings can't be retrieved";
	$update_message = "";
	if ($dbh) {
		$data = $app -> getUserTokenInfo('me');
		$id = $data["user"]["id"];
		//$live = $value;
		if ($live !== false) {
			try {
				$update = sprintf("UPDATE user_tokens SET live = %d WHERE id = %d AND rkid = %d", $live,$id,$rk->userID);
				$dbh -> exec($update);
				$update_message = "<div class=\"panel callout radius\">";
				$update_message .= "<h5>Settings saved</h5></div>";
				//$live = $value;
				//$update_message .= "<p>It's a little ostentatious, but useful for important content.</p></div>";
			} catch (exception $e) {
				$dberror = true;
				$update_message = "<div class=\"panel callout radius\">";
				$update_message .= "<h5>Couldn't save settings</h5></div>";
				$autoPostStatus = "Couldn't connect to database so settings couldn't be updated or retrieved";
			}
		} else {

			try {

				$select = "SELECT live FROM user_tokens WHERE id = " . $id . " AND rkid = " . $rk -> userID . " LIMIT 0,1";

				foreach ($dbh->query($select) as $row) {
					$live = $row['live'];
				}

			} catch (exception $e) {
				$dberror = true;
				$update_message = "<div class=\"panel callout radius\">";
				$update_message .= "<h5>Couldn't load settings</h5></div>";
				$autoPostStatus = "Couldn't connect to database so settings couldn't be updated or retrieved";
			}
		}
		
		if ($live == 0)
		{
			$autoPostStatus = "live posting is turned off";
		}
		if ($live & 1) {
			$postChecked = 'checked="checked"';
			$autoPostStatus = "live posting will be made to your timeline";
		}
		if ($live & 2) {
			$patterChecked = 'checked="checked"';
			$autoPostStatus = "live posting will be made to the #adnfitnesschallenge patter room";
		}
		if ($live == 3) {
			$autoPostStatus = "live posting will be broadcast to both the #adnfitnesschallenge patter room and your time line";
		}
	}
	include_once 'templates/settingsForm.php';
}
?>