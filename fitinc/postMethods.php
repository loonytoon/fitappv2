<?php
if (!$INCLUDE_HASH || $INCLUDE_HASH != "c43a1883e171eebc0133785753745816") {
	header("HTTP/1.1 404 NOT FOUND");
	die ;
}


function getDistance($v_td, $distArray) {
	$dist = 0;
	if ($v_td < 100)
		$dist = floor($v_td) . " m";
	else {
		$dist = round($v_td * $distArray['distance_convert'], 2) . ' ' . $distArray['distance_unit'];
		$dist .= ' ( ' . round($v_td * $distArray['other_convert'], 2) . ' ' . $distArray['other_unit'] . ' )';
	}

	return $dist;
}

function buildText($value, $dist) {
	$_rk = RK;

	$text = 'I’ve been ' . strtolower($value -> type) . ' – ' . $dist . ' in ';
	$text .= unix2human(floor($value -> duration)) . '. ' . round($value -> total_calories, 2) . ' cal.';
	$text .= ' ' . "$_rk\n #" . HASHTAG;

	return $text;
}

function buildAnnotations($link) {
	$annotations = array();

	$crosspost = array("type" => 'net.app.core.crosspost', 'value' => array('canonical_url' => $link));

	$annotations[] = $crosspost;

	$server = array("type" => "net.fit-app.server", "value" => array("code" => "c", "host" => "textdrive"));

	$annotations[] = $server;

	$annotations[] = array("type" => "net.fit-app.auto", "value" => array("matic" => 1));

	return $annotations;
}

function buildEntities($text, $link,$enc) {
	$_rk = RK;
	
	return array("links" => array( array("pos" => mb_strrpos($text, $_rk, 0, $enc), "len" => strlen($_rk), "url" => $link)), "parse_links" => true);
}

function postToAdn() {

}

function postToPatter() {

}

function postToPatterB() {

}

?>