<?php
    include("db_connect.php");
    
	$email = $_POST["email"];
    $password = $_POST["password"];
    
	$insert = mysqli_query($db, "UPDATE users set password = md5('$password'), lockedPin = 0, triesPin = 0, tries = 0 WHERE email = '$email'");
	if($insert) {
		echo 'changed';
	}
	else {
		echo 'error';
	}