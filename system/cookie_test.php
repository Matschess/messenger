<?php



$result2 = mysqli_query($db, "SELECT * FROM users WHERE email = '$email' ");
		$row2 = mysqli_fetch_object($result2);

setcookie("TestCookie", $row2-->username);

echo $_COOKIE["TestCookie"];

?>