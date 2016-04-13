<?php

$job = $_GET["job"];

if ($job == "validateGroupName") {
    $groupName = $_GET["groupName"];
    if (validateGroupName($groupName)) {
        echo "validated";
    } else {
        echo "error";
    }
} elseif ($job == "validateGroupMembers") {
    $toRoot = "../";
    include($toRoot . "variables/user_id.php");

    include("db_connect.php");

    $error;
    $groupName = $_GET["groupName"];
    if (validateGroupName($groupName)) {
        $groupMembers = array_unique($_GET["groupMembers"]);
        for ($i = 0; $i < count($groupMembers); $i++) {
            $friend_id = $groupMembers[$i];
            $friendExistsQuery = mysqli_query($db, "SELECT id FROM contacts WHERE user_id = $user_id && friend_id = $friend_id");
            if (!mysqli_num_rows($friendExistsQuery)) {
                $error = true;
                break;
            }
        }
    }
    else $error = true;

    if (!$error) {
        if ($groupCreate = mysqli_query($db, "INSERT INTO chats (groupname) VALUE ('$groupName')")) {
            for ($i = 0; $i < count($groupMembers); $i++) {
                $friend_id = $groupMembers[$i];
                $addUserToGroup = mysqli_query($db, "INSERT INTO groupmembers (chat_id, user_id) VALUE (LAST_INSERT_ID(), '$friend_id')");
            }
            echo "validated";
        }
    } else {
        echo "error";
    }
}

function validateGroupName($groupName)
{
    if ($groupName) {
        if (strlen($groupName) <= 60) {
            return true;
        }
    }
}