<nav class="row">
		<div class="small-12 columns">
			<?php
			 if ($count > 5)
			 {
			 	?>
			 	<ul class="button-group even-2">
  <li><a href="./?rkCount=<?php echo $count + 5; ?>&openActivity=<?=$openActivity; ?>"
class="button expand">Show more activities</a></li>
<li><a href="./?rkCount=<?php echo $count - 5; ?>&openActivity=<?=$openActivity; ?>"
class="button expand">Show fewer activities</a></li>

</ul>
<?php
}
else
{
?>
<a href="./?rkCount=<?php echo $count + 5; ?>&openActivity=<?=$openActivity; ?>"
class="button expand">Show more activities</a>
<?php
}
?>
</div>
</nav>
</section>
