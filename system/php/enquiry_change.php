<?php
	include("db_connect.php");

	$job = $_POST["job"];
	
	if($job == "delete") {
		$id = $_POST["id"];
		$delete = mysqli_query($db, "DELETE FROM contacts WHERE id = '$id'");
		if($delete) {
			echo 'changed';
		}
		else {
			echo 'error';
		}
	}
	elseif($job == "accept") {
		$id = $_POST["id"];
		$insert = mysqli_query($db, "UPDATE contacts SET accepted = 1 WHERE id = $id");
		if($insert) {
			$result = mysqli_query($db, "SELECT user_id, friend_id FROM contacts WHERE id = $id");
			$row = mysqli_fetch_object($result);
			$user_id = $row->user_id;
			$friend_id = $row->friend_id;
			$result2 = mysqli_query($db, "SELECT username FROM users WHERE id = $user_id");
			$row2 = mysqli_fetch_object($result2);
			$friend = $row2->username;
			$insert = mysqli_query($db, "INSERT INTO contacts (user_id, friend_id, friend, accepted) VALUES ($friend_id, $user_id, '$friend', 1)");
			echo 'changed';
		}
		else {
			echo 'error';
		}
	}
	else {
		echo 'error';
	}
?>