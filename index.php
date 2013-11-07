<?php

$INCLUDE_HASH = "c43a1883e171eebc0133785753745816";

//change path to the location of your config file
require_once $_SERVER['DOCUMENT_ROOT'] . '/../fitappv2/' . 'fitconfig.php';
require_once APPDOT_PATH . 'EZAppDotNet.php';

require_once FA_PATH . 'commons.php';
require_once FA_PATH . 'templates.php';

require_once RK_PATH . 'vendor/autoload.php';
require_once RK_PATH . 'lib/runkeeperAPI.class.php';

$app = new EZAppDotNet();
$url = $app -> getAuthUrl();

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

		<?php
		if (!isADNAuthed($app)) {
			template_signedOutHeader();
			template_adnLoginButton($url);
		} else if (isADNAuthed($app) && !isRKAuthed()) {
			template_adnSignedInHeader($app);
			template_rkLoginButton($rk);
		} else {
			$rk->setRunkeeperToken($_SESSION['runkeeperAPIAccessToken']);
			
			$rkActivities = $rk -> doRunkeeperRequest('FitnessActivityFeed', 'Read');
			$user_info = $rk -> doRunkeeperRequest('User', 'Read');
			$settings_read = $rk -> doRunkeeperRequest('Settings', 'Read');
			$profile_read = $rk -> doRunkeeperRequest('Profile', 'Read');
			
			template_signedInHeader($app, $profile_read);
			
			$count = getActivitiesToDisplay(count($rkActivities->items),intval($_GET['rkCount']));
			
			if (key_exists('openActivity', $_GET) && 0 < intval($_GET['openActivity']))
			{
				$openActivity = $_GET['openActivity'];
			}
			else
			{
				$openActivity = 0;
			}
			template_activitiesStart();
			tempate_activitiesDisplay($rkActivities,$user_info,$settings_read,$profile_read,$count,$openActivity);
			template_activitiesEnd($count,$openActivity);
			
		}
		?>

		<!--  <script>
		document.write('<script src=' +
		('__proto__' in {} ? 'javascripts/vendor/zepto' : 'javascripts/vendor/jquery') +
		'.js><\/script>')
		</script>

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
