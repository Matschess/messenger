<?php
include("db_connect.php");

$email = mysqli_real_escape_string($db, htmlspecialchars(trim($_POST["email"])));

$result = mysqli_query($db, "SELECT lockedLogin FROM users WHERE email = '$email'");
$row = mysqli_fetch_object($result);
if ($row) {
    if ($row->lockedLogin) {
        echo "locked";
    } else {
        // Generate PIN
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $shuffled = str_shuffle($str);

        // This will echo something like: bfdaec
        $shuffled = substr($shuffled, 0, 5);
        $insert = mysqli_query($db, "UPDATE users SET lockedPin = 1, triesPin = 0, pin = '$shuffled' WHERE email = '$email'");
        if ($insert) {
            echo 'started';
        } else {
            echo 'error';
        }
    }
} else {
    echo 'error';
}
?>