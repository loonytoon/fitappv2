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

function tempate_activitiesDisplay($rkActivities, $user_info, $settings_read,$profile_read, $count,$openActivity) {
	$distance_unit = "km";
	$distance_convert = 0.001;

	if ($settings_read -> distance_units != $distance_unit) {
		$other_unit = $distance_unit;
		$other_convert = $distance_convert;
		$distance_unit = 'mi';
		$distance_convert *= 0.621371192;
	} else {
		$other_unit = 'mi';
		$other_convert = $distance_convert * 0.621371192;
	}
	
	for ($i = 0;$i <$count;$i++)
	{
		$value = $rkActivities->items[$i];
		$da =new DateTime($value->start_time);
		$activity_id = str_ireplace("/fitnessActivities/", "", $value -> uri);
		if ($openActivity == $activity_id)
		{
			$openStyle = 'style="display:block;"';
			$hideButton = 'style="display:none;"';
		}
		else
		{
				$openStyle = "";
				$hideButton = "";
		}
		include "templates/activity.php";
	}
	
	
}

function template_activitiesEnd($count,$openActivity) {
	
	?>
	<nav class="row">
		<div class="small-12 columns">
			<?php
			 if ($count > 5)
			 {
			 	?>
			 	<ul class="button-group even-2">
  <li><a href="./?rkCount=<?php echo $count+5;?>&amp;openActivity=<?=$openActivity;?>" class="button expand">Show more activities</a></li>
  <li><a href="./?rkCount=<?php echo $count-5;?>&amp;openActivity=<?=$openActivity;?>" class="button expand">Show fewer activities</a></li>
 
</ul>
			 	<?php
			 }
			 else
			 	{
			 		
			 
			?>
			<a href="./?rkCount=<?php echo $count+5;?>&amp;openActivity=<?=$openActivity;?>" class="button expand">Show more activities</a>
			<?php
				}
			?>
		</div>
	</nav>
</section>
	<?php
	
	
}
?>