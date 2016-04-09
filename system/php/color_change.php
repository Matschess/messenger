<?php
$toRoot = "../";
include($toRoot . "variables/user_id.php");

include("db_connect.php");

$color = $_POST["color"];

$update = mysqli_query($db, "UPDATE users SET color = '$color' WHERE id = $user_id");
if ($update) {
    setcookie("messengerColor", $color, 0, '/'); // Set cookie until browser is closed
    echo 'changed';
} else {
    echo 'error';
}