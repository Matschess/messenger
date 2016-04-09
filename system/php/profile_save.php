<?php
$toRoot = "../";
include($toRoot . "variables/user_id.php");

include("db_connect.php");

$job = $_POST["job"];

if ($job == "status") {
    $statustext = $_POST["statustext"];
    $update = mysqli_query($db, "UPDATE users SET statustext = '$statustext' WHERE id = $user_id");
    if ($update) {
        echo "updated";
    } else {
        echo "error";
    }
} elseif ($job == "visibility") {
    $isPublic = $_POST["isPublic"];
    $update = mysqli_query($db, "UPDATE users SET isPublic = $isPublic WHERE id = $user_id");
    if ($update) {
        echo "updated";
    } else {
        echo "error";
    }
}
?>