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
                $groupMembers = mysqli_query($db, "SELECT id FROM groupmembers WHERE chat_id = $chat_id");
                if (!mysqli_num_rows($groupMembers)) {
                    $deleteGroup = mysqli_query($db, "DELETE FROM chats WHERE id = $chat_id");
                }
                echo "deleted";
            }
        } else echo "error";
    } else echo "error[noDeleteSelf]";
} else if ($job == "leave") {
    $chat_id = $_COOKIE["chat_id"];
    $deleteAllowed = mysqli_query($db, "SELECT id FROM groupmembers WHERE $chat_id = $chat_id && user_id = $user_id");
    if (mysqli_num_rows($deleteAllowed)) {
        if ($deleteMember = mysqli_query($db, "DELETE FROM groupmembers WHERE chat_id = $chat_id && user_id = $user_id")) {
            $groupMembers = mysqli_query($db, "SELECT id FROM groupmembers WHERE chat_id = $chat_id");
            if (!mysqli_num_rows($groupMembers)) {
                $deleteGroup = mysqli_query($db, "DELETE FROM chats WHERE id = $chat_id");
            }
            echo "left";
        }
    } else echo "error";
} else if ($job == "add") {
    $friend_id = $_GET["friend_id"];
    $chat_id = $_COOKIE["chat_id"];
    $groupExists = mysqli_query($db, "SELECT id FROM groupmembers WHERE chat_id = $chat_id && user_id = $user_id");
    if (mysqli_num_rows($groupExists)) {
        $groupExists = mysqli_query($db, "SELECT id FROM groupmembers WHERE chat_id = $chat_id && user_id = $friend_id");
        if (!mysqli_num_rows($groupExists)) {
            if (mysqli_query($db, "INSERT INTO groupmembers (chat_id, user_id) VALUES ('$chat_id', '$friend_id')")) {
                echo "added";
            } else echo "error";
        } else echo "error";
    } else echo "error";
} else if ($job == "promote") {
    $member_id = $_GET["member_id"];
    $chat_id = $_COOKIE["chat_id"];
    $groupExists = mysqli_query($db, "SELECT id FROM groupmembers WHERE chat_id = $chat_id && user_id = $user_id");
    if (mysqli_num_rows($groupExists)) {
        $groupExists = mysqli_query($db, "SELECT id FROM groupmembers WHERE chat_id = $chat_id && user_id = $member_id");
        if ($row = mysqli_fetch_object($groupExists)) {
            $id = $row->id;
            if (mysqli_query($db, "UPDATE groupmembers SET admin = true WHERE id = $id")) {
                echo "promoted";
            } else echo "error";
        } else echo "error";
    } else echo "error";
} else if ($job == "degrade") {
    $member_id = $_GET["member_id"];
    $chat_id = $_COOKIE["chat_id"];
    $groupExists = mysqli_query($db, "SELECT id FROM groupmembers WHERE chat_id = $chat_id && user_id = $user_id");
    if (mysqli_num_rows($groupExists)) {
        $groupExists = mysqli_query($db, "SELECT id FROM groupmembers WHERE chat_id = $chat_id && user_id = $member_id");
        if ($row = mysqli_fetch_object($groupExists)) {
            $id = $row->id;
            if (mysqli_query($db, "UPDATE groupmembers SET admin = false WHERE id = $id")) {
                echo "degraded";
            } else echo "error";
        } else echo "error";
    } else echo "error";
} else if ($job == "editGroupName") {
    $groupName = trim($_GET["groupName"]);
    $chat_id = $_COOKIE["chat_id"];
    if ($groupName != "") {
        if (mysqli_query($db, "UPDATE chats SET groupname = '$groupName' WHERE id = $chat_id")) {
            echo "edited";
        } else echo "error";
    } else echo "error";
}