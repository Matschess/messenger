<?php
// Maximale Versuche bis Kontosperrung
$maxTries = 5;

    include("db_connect.php");
    
	$email = $_POST["email"];
    $pin = $_POST["pin"];
    
    $result = mysqli_query($db, "SELECT * FROM users WHERE email = '$email'");
	$row = mysqli_fetch_object($result);
	if($row->triesPin >= $maxTries) {
		echo "lockedPin";
	}
	else if($row->tries >= $maxTries) {
		echo "locked";
	}
	else if(!$row->lockedPin or !$row->pin) {
		echo "lockedError";
	}
	else {
		$result2 = mysqli_query($db, "SELECT * FROM users WHERE email = '$email' && lockedPin = 1 && pin = '$pin'");
		$row2 = mysqli_fetch_object($result2);
		if($row2) {
			$update = mysqli_query($db, "UPDATE users set lockedPin = 0, pin = '' WHERE email = '$email'");
			echo 'authorized';
		}
		else {
			if($row->triesPin == $maxTries - 1) {
				$insert = mysqli_query($db, "UPDATE users set lockedPin = 0, triesPin = 0, pin = '' WHERE email = '$email'");
				echo "lockedPin";
			}
			else {
			$insert2 = mysqli_query($db, "UPDATE users set triesPin = triesPin+1 WHERE email = '$email'");
			echo "denied";
			}
		}
	}
?>