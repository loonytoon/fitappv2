<?php

if (!$INCLUDE_HASH || $INCLUDE_HASH != "c43a1883e171eebc0133785753745816") {
	header("HTTP/1.1 404 NOT FOUND");
	die ;
}

//change path to the location of your config file

require_once APPDOT_PATH . 'EZAppDotNet.php';

require_once FA_PATH . 'commons.php';
require_once FA_PATH . 'templates.php';

require_once RK_PATH . 'vendor/autoload.php';
require_once RK_PATH . 'lib/runkeeperAPI.class.php';

$app = new EZAppDotNet();
$url = $app -> getAuthUrl();
$rk = new runkeeperAPI(RK_API_YML);

if (!isADNAuthed($app) || !isRKAuthed()) {
	//this can probably be done better
	header("location: .");
} else {

	$rk -> setRunkeeperToken($_SESSION['runkeeperAPIAccessToken']);
	$profile_read = $rk -> doRunkeeperRequest('Profile', 'Read');
	//echo "activity_id:".$activity_id;
	
	//$fa_read = $rk -> doRunkeeperRequest('FitnessActivities', 'Read','','/fitnessActivities/"'.$activity_id);
	
	/*if ($fa_read) {
			echo "worked";
			print_r($fa_read);
		}
		else {
			echo $rk->api_last_error;
			//print_r($rk->request_log);
		}
	die("fa_read");*/
	if ($profile_read && $profile_read !== true) {
		$msg = array();	
		 
		$msg['text'] = verifyPostText($_POST['message']);

		// = UTF-8
		$link = verifyAnnoLnk($_POST['annolnk'],$activity_id);

		$msg['annotations'] = buildAnnotations($link);
		$params = array('annotations' => $msg['annotations']);

		$enc = mb_detect_encoding($msg['text']);
		if (strpos($msg['text'], RK) > 0) {
			$msg['entities'] = buildEntities($msg['text'], $link, $enc);
			$params['entities'] = $msg['entities'];
		}

		$_postTo = $_POST['postTo'];

		if ($_postTo & 2) {
			echo "1st 2<br/>";
			$msg['text'] .= ' ' . UFO;

			$params['entities']['links'][] = array("len" => 3, "pos" => mb_strrpos($msg['text'], UFO, 0, $enc), "url" => "http://patter-app.net/room.html?channel=" . P_CHANNEL);

			// additional annotations
			// {"type":"net.app.core.channel.invite","value":{"channel_id":"23835"}}]}
			$params['annotations'][] = array('type' => 'net.app.core.channel.invite', 'value' => array('channel_id' => P_CHANNEL));

		}

		//echo $_postTo;
		$x = "";
		if ($_postTo & 1)
		{
			echo "1";
			$x = $app -> createPost($msg['text'], $params);
		}

		if ($_postTo == 3) {
			echo "patter broadcast 3";
			// additional annotations
			// {"type": "net.patter-app.broadcast","value": {"id": "9578966","url": "https://alpha.app.net/cn/post/9578966"}}

			$msg['annotations'][] = array('type' => 'net.patter-app.broadcast', 'value' => array('id' => $x['id'], 'url' => $x['canonical_url']));

		}

		if ($_postTo & 2)
		{
			echo "2";
			$app -> createMessage(P_CHANNEL, $msg);
		}

	}
}
?>