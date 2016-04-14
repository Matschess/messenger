<?php
$toRoot = "../";
include($toRoot . "variables/user_id.php");

$job = $_GET["job"];
include("db_connect.php");

if ($job == "delete") {
    $member_id = $_GET["member_id"];

    if ($member_id != $user_id) {
        $chat_id = $_COOKIE["chat_id"];
        $deleteAllowed = mysqli_query($db, "SELECT id FROM groupmembers WHERE $chat_id = $chat_id && user_id = $member_id");
        if (mysqli_num_rows($deleteAllowed)) {
            if ($deleteMember = mysqli_query($db, "DELETE FROM groupmembers WHERE chat_id = $chat_id && user_id = $member_id")) {
                echo "deleted";
            }
        }
        else echo "error";
    } else echo "error[noDeleteSelf]";
}