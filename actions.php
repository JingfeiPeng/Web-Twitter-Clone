<?php
	include("functions.php");
	if ($_GET['action'] == "loginSignup"){
		$error = "";
		if (!$_POST['email']){
			$error = "Missing Email Address";
		} else if (!$_POST['password']){
			$error = "Missing password";
		} else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		  $error = "Invalid format for email address."; 
		}
		if ($error !=""){
			echo $error;
			exit();
		}
		if ($_POST['loginActive'] == 0){
			$query = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link,$_POST['email'])."' LIMIT 1";
			$result = mysqli_query($link,$query);
			if (mysqli_num_rows($result) > 0) $error = "Email address is taken.";
		    else{
				$query = "INSERT INTO users (email,password) VALUES ('".mysqli_real_escape_string($link,$_POST['email'])."',
				'".mysqli_real_escape_string($link,$_POST['password'])."')";
				if (mysqli_query($link,$query)){
					$_SESSION['id'] = mysqli_insert_id($link);
					$query = "UPDATE users SET password ='".md5(md5($_SESSION['id']).$_POST['password'])."' WHERE id =".$_SESSION['id']." LIMIT 1";
					mysqli_query($link,$query);
					echo "1";
				} else{
					$error = "Couldn't create user";
				}
			}
		} else {
			$query = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link,$_POST['email'])."' LIMIT 1";
			$result = mysqli_query($link,$query);
			$row = mysqli_fetch_assoc($result);
			if ($row['password'] == md5(md5($row['id']).$_POST['password'])){
				$_SESSION['id'] = row['id'];
				echo "1";
			} else{
				$error = "Incorrect Username/password Combination";
			}
		}
		if ($error !=""){
			echo $error;
			exit();
		}
	}
	
	if ($_GET['action'] == 'toggleFollow'){
        $query = "SELECT * FROM isFollowing WHERE follower = ". mysqli_real_escape_string($link, $_SESSION['id'])." AND isFollowing = ". mysqli_real_escape_string($link, $_POST['userId'])." LIMIT 1";
		$result = mysqli_query($link, $query);
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			mysqli_query($link, "DELETE FROM isFollowing WHERE id = ". mysqli_real_escape_string($link, $row['id'])." LIMIT 1");
			echo "1";
		} else {
			mysqli_query($link, "INSERT INTO isFollowing (follower, isFollowing) VALUES (". mysqli_real_escape_string($link, $_SESSION['id']).", ". mysqli_real_escape_string($link, $_POST['userId']).")");
			echo "2";			
		}
	}



?>