	<article class="activity">
		<div class="row">
					<div class="small-12 large-10 columns">
						<h4><?php echo $value -> type; ?> on <?php echo $da->format("l jS F Y \a\\t g:ia"); ?></h4>
						<span>Distance: <?php

 $v_td = $value -> total_distance;
 if($v_td < 100)
   $dist = floor($v_td) . " m";
 else {
   $dist = round($v_td * $distance_convert, 2) . ' ' . $distance_unit;
   $dist .= ' ( ' . round($v_td * $other_convert, 2) . ' ' . $other_unit . ' )';
 }

							echo $dist;
						?></span> <span>Duration: <?php echo unix2human(floor($value->duration));?></span>
					</div>

					<div class="small-12 large-2 columns">
						<a <?=$hideButton;?> href="index.php?rkCount=<?=$count;?>&amp;openActivity=<?=$activity_id;?>" class="button round expand openPostBox" data-post-form-id="pf<?php echo $i; ?>">Post to adn</a>
					</div>

					</div>
					<div class="row">
								<form class="postbox" id="pf<?php echo $i; ?>" method="post" action="post.php?activity_id=<?=$activity_id;?>&rkCount=<?php echo $count;?>" <?=$openStyle;?>>
									<div class="small-12 large-10 columns">
									<label>Message</label><br/>
<textarea name="message" class="postContent" rows=3>I&#8217;ve been <?php echo strtolower($value -> type); ?> - <?php echo $dist;?> in <?php echo unix2human(floor($value->duration));?>. <?php echo round($value->total_calories, 2);?> cal. <?php echo RK;?>
 #<?php echo HASHTAG ?></textarea>
<input class="linkAnnotation" name="annolnk" type="hidden" value=<?php echo $profile_read->profile; ?>/activity/<?php echo $activity_id; ?>>
		<button name="postTo" id="postToAdn" value="1" class="button postToAdn round" data-post-form-id="pf<?php echo $i; ?>">Post</button>
		<button name="postTo" id="postToPatter" value="2" class="button postToPatter round" data-post-form-id="pf<?php echo $i; ?>">Patter</button>
		<button name="postTo" id="postToPatterB" value="3" class="button postToPatterB round" data-post-form-id="pf<?php echo $i; ?>">Broadcast</button>
</div>
<div class="small-12 large-2 columns">
									<a href="./?rkCount=<?=$count;?>" data-post-form-id="pf<?php echo $i; ?>" class="small button expand round secondary cancelPost">cancel</a>
									</div>
								</form>
								</div>
				</article>