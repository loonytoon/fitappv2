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
						<a <?=$hideButton;?> data-pboxid="<?php echo $i;?>" data-countfield-id="count<?php echo $i?>"  href="index.php?rkCount=<?=$count;?>&amp;openActivity=<?=$activity_id;?>" class="button round expand openPostBox<?php echo $postToButtonClass;?>" data-post-form-id="<?php echo $i; ?>"><?php echo $postToButtonText;?></a>
					</div>

					</div>
					<div class="row">
								<form class="postbox" id="pf<?php echo $i; ?>" method="post" action="post.php?activity_id=<?=$activity_id;?>&rkCount=<?php echo $count;?>" <?=$openStyle;?>>
									<div class="small-12 large-10 columns">
									<label for="message<?php echo $i?>">Message</label>
									<br/>
<textarea name="message" class="postContent" data-countfield-id="count<?php echo $i?>" rows=3>I&#8217;ve been <?php echo strtolower($value -> type); ?> - <?php echo $dist;?> in <?php echo unix2human(floor($value->duration));?>. <?php echo round($value->total_calories, 2);?> cal. <?php echo RK;?>
 #<?php echo HASHTAG ?></textarea>
 <div class="row">
 	<div class="large-1 small-3 columns">
 	<input type="text" value="256" id="count<?php echo $i?>" />
 </div>
 <div class="large-2 small-4 columns">
<input class="linkAnnotation" name="annolnk" type="hidden" value=<?php echo $profile_read->profile; ?>/activity/<?php echo $activity_id; ?>>
		<button name="postTo" id="postToAdn<?php echo $i?>" value="1" class="postButton button postToAdn round<?php echo $postBtnClass;?>" data-post-form-id="<?php echo $i; ?>">Post</button>
		</div>
		<div class="large-2 small-5 columns"><button name="postTo" id="postToPatter<?php echo $i?>" value="2" class="postButton button postToPatter round<?php echo $patterBtnClass;?>" data-post-form-id="<?php echo $i; ?>">Patter</button>
		</div>
		<div class="small-12 large-7 columns"><button name="postTo" id="postToPatterB<?php echo $i?>" value="3" class="postButton button postToPatterB round<?php echo $broadcastBtnClass;?>" data-post-form-id="<?php echo $i; ?>">Broadcast</button>

</div></div>

</div>
<div class="small-12 large-2 columns">
									<a href="./?rkCount=<?=$count;?>" data-pboxid="<?php echo $i; ?>" class="small button expand round secondary cancelPost">cancel</a>
									</div>
								</form>
								</div>
				</article>