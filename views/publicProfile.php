<div class="container maincontainer">
	<div class="row">
	  <div class="col-8">
		<?php if ($_GET['userid']){
			displayTweets($_GET['userid']);
		}else { ?>
		<h2> Active users</h2>
		<?php displayUsers(); ?>
		<?php } ?>
	  </div>
	  <div class="col-4">
		<?php displaySearch(); ?>
		<hr>
		<?php displayTweetBox(); ?>
	  </div>
	</div>
	

</div>