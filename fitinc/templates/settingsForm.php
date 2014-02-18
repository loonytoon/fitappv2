<section class="row">
	<div class="small-12 columns content">

		<h2>Settings</h2>
		<a class="button round small" href="/">Show activities</a>
		<?php echo $update_message; ?>
		<div class="row">
				<div class="small-6 columns">
					
		<form method="post">
			<fieldset>
				
			<legend>Automatic posting</legend>
			
			
					<label>Where should automatic posts go?</label>
					<input id="checkbox1" type="checkbox" name="postAdn" value="1" <?php echo $postChecked; ?>>
					<label for="checkbox1">Post</label>
					<input id="checkbox2" type="checkbox" name="postPatter" value="2" <?php echo $patterChecked; ?>>
					<label for="checkbox2">Patter</label>
					<aside>
						select both to broadcast and none to turn off
					</aside>
					<button class="button round small disabled" type="reset">Reset</button>
					<button class="button round small" name="saveSettings" type="submit">Save</button>
					</fieldset>
		</form>
				</div>
				<div class="small-6 columns"><div class="panel"><?php echo $autoPostStatus; ?></div></div>
			</div>
			
	</div>
</section>
