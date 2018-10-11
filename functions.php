<?php
	session_start();
	$link = mysqli_connect("shareddb1e.hosting.stackcp.net","twitter-3637c24d","21903ujkjnsad","twitter-3637c24d");
	if (mysqli_connect_error()){
		print_r(mysqli_connect_error());
		exit();
		
	}
	if ($_GET['function'] == "logout"){
		session_unset();
	}
	

	function time_since($since) {
		$chunks = array(
			array(60 * 60 * 24 * 365 , 'year'),
			array(60 * 60 * 24 * 30 , 'month'),
			array(60 * 60 * 24 * 7, 'week'),
			array(60 * 60 * 24 , 'day'),
			array(60 * 60 , 'hour'),
			array(60 , 'minute'),
			array(1 , 'second')
		);

		for ($i = 0, $j = count($chunks); $i < $j; $i++) {
			$seconds = $chunks[$i][0];
			$name = $chunks[$i][1];
			if (($count = floor($since / $seconds)) != 0) {
				break;
			}
		}

		$print = ($count == 1) ? '1 '.$name : "$count {$name}s";
		return $print;
	}

	function displayTweets($type){
		global $link;
		if ($type == "public"){
			$whereClause = "";
		} else  if ($type == "isFollowing"){
			$query = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id']);
			$result = mysqli_query($link, $query);
			$whereClause = "";
			while ($row =mysqli_fetch_array($result)){
				if ($whereClause == ""){
					$whereClause = "WHERE";
				} else {
					$whereClause .= " OR";
				}
				$whereClause .=" userid = ".$row['isFollowing'];
			}
			if ($whereClause == "") $whereClause = "WHERE userid = -1";
		} else if ($type == "yourtweets"){
			$whereClause = "WHERE userid = ".mysqli_real_escape_string($link,$_SESSION['id']);
		} else if ($type == "search"){
			echo '<p>Showing Results for: "'.mysqli_real_escape_string($link,$_GET['q']).'" </p>';
			$whereClause = "WHERE tweet LIKE '%".mysqli_real_escape_string($link,$_GET['q'])."%'";
		} else if (is_numeric($type)){
			$userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link, $type)." LIMIT 1";
			$userQueryResult = mysqli_query($link,$userQuery);
			$user = mysqli_fetch_assoc($userQueryResult);
			// change to nicknames:
			echo "<h2>".mysqli_real_escape_string($link,$user['email'])."'s tweets</h2>";
			$whereClause = "WHERE userid = ".mysqli_real_escape_string($link,$type);
		}
		
		$query = "SELECT * FROM tweets ".$whereClause." ORDER BY datetime DESC LIMIT 10";
		$result = mysqli_query($link,$query);
		if (mysqli_num_rows($result) == 0){
			echo "There are no tweets to display";
		} else{ //display tweets
			while ($row  = mysqli_fetch_assoc($result)){
				$userQuery = "SELECT * FROM users WHERE id = ".mysqli_real_escape_string($link,$row['userid'])." LIMIT 1";
				$userQueryResult = mysqli_query($link,$userQuery);
				$user = mysqli_fetch_assoc($userQueryResult);
				
				echo "<div class='tweet'><p><a href='?page=publicProfile&userid".$user['id']	."'>".$user['email']."</a><span class='time'> - ".time_since(time() - strtotime($row['datetime']))." ago:</span></p>";
				echo "<p>".$row['tweet']."</p>";
				echo "<p><a class='toggleFollow' data-userId='".$row['userid']."'>";
				
				$isFollowingQuery = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($link, $row['userid'])." LIMIT 1";
				$isFollowingQueryResult = mysqli_query($link, $isFollowingQuery);
				if (mysqli_num_rows($isFollowingQueryResult) > 0) {
					echo "Unfollow";
				} else {
					echo "Follow";
				}
				echo "</a></p></div>";
				
			}
		}
	}
	
	function displaySearch(){
		echo '<form><div class="form-inline">
			  <div class="form-group mb-2">
				<input type="hidden" name="page" value="search">
				<input type="text" name="q" style="margin-right:5px" class="form-control" id="seach" value="Search">
			  </div>
			  <button type="submit" class="btn btn-primary mb-2">Search Tweets</button>
			</div> </form>';
	}
	
	function displayTweetBox(){

		if ($_SESSION['id'] >= 0){
			echo '<div id="tweetSuccess" class="alert alert-success">Tweet posted successfully </div>
				  <div id="tweetFail" class="alert alert-danger"></div>
				<div class="form">
				  <div class="form-group mb-2">
					<textarea style="margin-right:5px" class="form-control" id="tweetContent"></textarea>
				  </div>
				  <button id="postTweetButton" class="btn btn-primary mb-2">Post Tweet</button>
				</div>';
		
		}
	}
	
	function displayUsers(){
		global $link;
		$query = "SELECT * FROM users LIMIT 10";
		$result = mysqli_query($link,$query);
		if (mysqli_num_rows($result) == 0){
			echo "There are no users to display";
		} else{ //display users
			while ($row  = mysqli_fetch_assoc($result)){
				// do show up the next 10;
				echo "<p> <a href='?page=publicProfile&userid=".$row['id']."'>".$row['email']."</a></p>";
			}
		}
		
	}
	
	
	
	
	

?>