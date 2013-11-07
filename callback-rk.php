<?php
$INCLUDE_HASH = "c43a1883e171eebc0133785753745816";
require_once dirname(__FILE__) . '/./fitconfig.php';

require_once APPDOT_PATH . 'EZAppDotNet.php';

require_once RK_PATH . 'vendor/autoload.php';
require_once RK_PATH     . 'lib/runkeeperAPI.class.php';


$app = new EZAppDotNet();
$url = $app->getAuthUrl();

// check that the user is signed in
if ($app->getSession()) {

	try {
		$denied = $app->getUser();
	//	print " error - we were granted access without a token?!?\n";
	//	exit;
	}
	catch (AppDotNetException $e) { // catch revoked access and existing session // Safari 6 doesn't like
		if ($e->getCode()==401) {
			print " success (could not get access)\n";
		}
		else {
			throw $e;
		}
		$app->deleteSession();
		header('Location: .'); die;
	}

// otherwise prompt to sign in
} else {

echo '<div id=userblk>';

	echo '<a href="'.$url.'"><u>Sign in using App.net</u></a>';
	if (isset($_SESSION['rem'])) {
		echo 'Remember me <input type="checkbox" id="rem" value="1" checked/>';
	} else {
		echo 'Remember me <input type="checkbox" id="rem" value="2" />';
	}
	?>
	<script>
	document.getElementById('rem').onclick = function(e){
		if (document.getElementById('rem').value=='1') {
			window.location='?rem=2';
		} else {
			window.location='?rem=1';
		};
	}
	</script>
	<?php
}

if($app->getSession()) {

$rk = new runkeeperAPI(RK_API_YML);

$rkToken = $rk->getRunkeeperToken($_REQUEST['code']);

if(!$rkToken) { // EZAppDotNetPHP getSession-type function call (?)
} else 
$_SESSION['runkeeperAPIAccessToken'] = $rk->readToken();

header('Location: .');

}
