 <div class="large-8 small-12 columns"><h1>fit <small>Do you feel healthy?</small></h1></div>
   <aside class="large-4 small-12 columns userinfo">
   	<div class="row">
   		<h3><?=$data['user']['name']; ?></h3>   		
   	</div>
   	<div class="row">
   		<div class="small-6 large-6 columns">
   			<img height=48 style="border:2px solid #000;" src="<?=$data['user']['avatar_image']['url']; ?>?h=48&amp;w=48" /><br />
   			
   		</div>
   		<div class="small-6 large-6 columns">
   			<a href=".?action=settings" class="button alert tiny round">Settings</a></span>
   			<a href="logout.php" class="button alert tiny round">Sign out</a></span>
   					
   		</div>
   	</div>
   	<div class="row">
   		
	Runkeeper:&#160;&#160;<a href="<?=$profile_read->profile;?>"><?=$profile_read->name;?></a>&#160;&#160;<?=$profile_read->athlete_type;?>
   	</div>
 </aside>