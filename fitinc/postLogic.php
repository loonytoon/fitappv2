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
$_postTo = 1;
$link = "";
$user_info = "";
$profile_read = "";

if (!isADNAuthed($app) || !isRKAuthed()) {
	//this can probably be done better
	header("location: .");
} else {

	$rk -> setRunkeeperToken($_SESSION['runkeeperAPIAccessToken']);
	$rkActivities = $rk -> doRunkeeperRequest('FitnessActivityFeed', 'Read');
	$profile_read = $rk -> doRunkeeperRequest('Profile', 'Read');
	$user_info = $rk -> doRunkeeperRequest('User', 'Read');
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
		
		$text = $msg['text'];
		// = UTF-8
		$link = verifyAnnoLnk($_POST['annolnk'], $activity_id);

		$msg['annotations'] = buildAnnotations($link);
		$params = array('annotations' => $msg['annotations']);

		$enc = mb_detect_encoding($msg['text']);
		echo $enc;
		if (strpos($msg['text'], RK) > 0) {
			$msg['entities'] = buildEntities($msg['text'], $link, $enc);
			$params['entities'] = $msg['entities'];
		}

		$msg['text'] = stripcslashes($msg['text']);
		//$msg['text'] = htmlentities($msg['text']);
		$_postTo = $_POST['postTo'];
		$text = stripcslashes($text);
	//	$text = htmlentities($text);
		//echo $msg['text'].'<br/>';
		//die($text);
		if ($_postTo & 2) {
			//echo "1st 2<br/>";
			$text .= ' ' . UFO;

			$params['entities']['links'][] = array("len" => 3, "pos" => mb_strrpos($text, UFO, 0, $enc), "url" => "http://patter-app.net/room.html?channel=" . P_CHANNEL);
			// additional annotations
			
			// {"type":"net.app.core.channel.invite","value":{"channel_id":"23835"}}]}
			$params['annotations'][] = array('type' => 'net.app.core.channel.invite', 'value' => array('channel_id' => P_CHANNEL));

		}

		//echo $_postTo;
		
		$x = "";
		if ($_postTo & 1 && strlen($text) < 257) {
			//echo "1";
			$x = $app -> createPost($text, $params);
		}

		if ($_postTo == 3 && strlen($text) < 257) {
			//echo "patter broadcast 3";
			// additional annotations
			// {"type": "net.patter-app.broadcast","value": {"id": "9578966","url": "https://alpha.app.net/cn/post/9578966"}}

			$msg['annotations'][] = array('type' => 'net.patter-app.broadcast', 'value' => array('id' => $x['id'], 'url' => $x['canonical_url']));

		}
		/*echo "<strong>";
		print_r($msg);
		echo(json_encode($msg));
		echo "</strong>";//*/
		if ($_postTo & 2) {
			//echo "2";
			$app -> createMessage(P_CHANNEL, $msg);
		}

	}

	try {

		//write to db
		$dbh = connectToDB();
		$data = $app -> getUserTokenInfo('me');

		$uid = 0;
		$select = "SELECT uid FROM user_tokens WHERE id = " . $data["user"]["id"] . " AND rkid =" . $user_info -> userID . " LIMIT 0,1";
		//die($select);
		//$rows = ;
		if ($dbh) {
			foreach ($dbh->query($select) as $row) {
				$uid = $row['uid'];
			}

			$insert_format = "INSERT INTO posted_activities (user_tokens_uid,activity,post,patter,broadcast) VALUES (%d,%d,%d,%d,%d)";
			//$update_format = '`post`=%d,`patter`=%d,`broadcast`=%d';
			$insert = sprintf($insert_format, $uid, $activity_id, $_postTo == 1 ? 1 : 0, $_postTo == 2 ? 1 : 0, $_postTo == 3 ? 1 : 0);
			$insert .= " on duplicate key update ";
			if ($_postTo == 1) {
				$insert .= '`post`=1';
			} else if ($_postTo == 2) {
				$insert .= '`patter`=1';
			} else if ($_postTo == 3) {
				$insert .= '`broadcast`=1';
			}
			//$insert .= sprintf($update_format,$_postTo == 1 ? 1 : 0, $_postTo == 2 ? 1 : 0, $_postTo == 3 ? 1 : 0);
			//die($insert);
			$dbh -> exec($insert);
			$_link = $profile_read -> profile . '/activity/' . $activity_id;
			$update = "UPDATE user_tokens SET _when = " . time() . ", _which = '" . $_link . "' WHERE uid = " . $uid;
			//die($update);
			$dbh -> exec($update);
		}
	} catch (PDOException $e) {
		//print "Error!: " . $e->getMessage() . "<br/>";
		//die();
		//handle this better
		$dberror = TRUE;
	}
	header("location: .?activity_id=$activity_id&rkCount=" . getActivitiesToDisplay(count($rkActivities -> items), key_exists('rkCount', $_GET) ? intval($_GET['rkCount']) : 5));
}
?>