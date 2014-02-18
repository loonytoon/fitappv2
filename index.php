<?php
$start = time();
$INCLUDE_HASH = "c43a1883e171eebc0133785753745816";

//change path to the location of your config file
require_once $_SERVER['DOCUMENT_ROOT'] . '/./' . 'fitconfig.php';

$action = substr(trim(strip_tags($_GET['action'])), 0, 10);
if (!in_array($action, $ALLOWED_ACTIONS)) {
	$action = null;
}
require_once FA_PATH . 'templates/globalheader.php';
include_once FA_PATH . 'phpfastcache/phpfastcache.php';

$cache = phpFastCache("files");

if (!isADNAuthed($app)) {
	template_signedOutHeader();
	template_adnLoginButton($url);
} else if (isADNAuthed($app) && !isRKAuthed()) {
	template_adnSignedInHeader($app);
	template_rkLoginButton($rk);
} else {
	$rk -> setRunkeeperToken($_SESSION['runkeeperAPIAccessToken']);
	$data = $app -> getUserTokenInfo('me');
	$profile_read = getRkProfile($rk, $data['user']['id']);
	$user_info = getRkUserInfo($rk, $data['user']['id']);

	//unset($data);
	//do the database thing
	$dbh = connectToDB();
	$value = false;
	if ($action == 'settings') {
		if (isset($_POST['saveSettings'])) {
			//print_r($_POST);
			//die;
			//do the saving thing.
			$value = verifySettingsForm($_POST);

		}
		//print_r($profile_read);
		template_signedInHeader($app, $profile_read);
		template_settingsForm($dbh, $user_info, $app, $value);
	} else {
		$rkActivities = getRkActivities($rk, $data['user']['id']);

		$settings_read = $rk -> doRunkeeperRequest('Settings', 'Read');

		updateUserInfo($dbh, $user_info, $profile_read, $app);

		template_signedInHeader($app, $profile_read);

		$count = getActivitiesToDisplay(count($rkActivities -> items), key_exists('rkCount', $_GET) ? intval($_GET['rkCount']) : 5);

		if (key_exists('openActivity', $_GET) && 0 < intval($_GET['openActivity'])) {
			$openActivity = $_GET['openActivity'];
		} else {
			$openActivity = 0;
		}
		template_activitiesStart();
		tempate_activitiesDisplay($rkActivities, $user_info, $settings_read, $profile_read, $count, $openActivity, $dbh);
		template_activitiesEnd($count, $openActivity);
	}
}
?>

<script src=javascripts/vendor/jquery.js></script>
<script>
	var maxlimit = 256;
	$(document).on('click', 'a.openPostBox', function(event) {
		event.preventDefault();
		var postBoxId = this.getAttribute("data-pboxid");
		var $postBox = $('#pf' + postBoxId);
		var that = $postBox.find("textarea");

		$postBox.show("slow");
		var a = textCounter(that[0], this.getAttribute('data-countfield-id'));
	});

	$(document).on('click', 'a.cancelPost', function(event) {
		event.preventDefault();
		var postBoxId = this.getAttribute("data-pboxid");
		$('#pf' + postBoxId).hide("slow");
	});
	
	$(document).on('click','button.postButton',function(event){
		if ($(this).hasClass('disabled'))
		{
			event.preventDefault();
		}
	});

	$(document).on('keyup', 'textarea.postContent', function(event) {

		var count = textCounter(this, this.getAttribute('data-countfield-id'));
		
		var postButton = $(this).parents('form').find('button.postToAdn');
		var broadcastButton = $(this).parents('form').find('button.postToPatterB');
		
		if (count > 256)
		{
			//disable the post and broadcast buttons
			 postButton.addClass('disabled');
			 broadcastButton.addClass('disabled');
		}
		else
		{
			postButton.removeClass('disabled');
			broadcastButton.removeClass('disabled');
		}
	});

	function textCounter(field, cnt) {
		var cntfield = document.getElementById(cnt);
		
		value = cntfield.value = field.value.length;
		return value;
	}
</script>
<!--
<script src="javascripts/foundation/foundation.js"></script>

<script src="javascripts/foundation/foundation.abide.js"></script>

<script src="javascripts/foundation/foundation.alerts.js"></script>

<script src="javascripts/foundation/foundation.clearing.js"></script>

<script src="javascripts/foundation/foundation.cookie.js"></script>

<script src="javascripts/foundation/foundation.dropdown.js"></script>

<script src="javascripts/foundation/foundation.forms.js"></script>

<script src="javascripts/foundation/foundation.interchange.js"></script>

<script src="javascripts/foundation/foundation.joyride.js"></script>

<script src="javascripts/foundation/foundation.magellan.js"></script>

<script src="javascripts/foundation/foundation.orbit.js"></script>

<script src="javascripts/foundation/foundation.placeholder.js"></script>

<script src="javascripts/foundation/foundation.reveal.js"></script>

<script src="javascripts/foundation/foundation.section.js"></script>

<script src="javascripts/foundation/foundation.tooltips.js"></script>

<script src="javascripts/foundation/foundation.topbar.js"></script>

<script>
$(document).foundation();
</script>-->
</body>
</html>
