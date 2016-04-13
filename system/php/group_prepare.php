<?php

$job = $_GET["job"];

if ($job == "validateGroupName") {
    $groupName = $_GET["groupName"];
    if ($groupName) {
        if (strlen($groupName) <= 60) {
            echo "validated";
        } else echo "error";
    } else echo "error";
} elseif ($job == "validateGroupMembers") {
    $toRoot = "../";
    include($toRoot . "variables/user_id.php");

    include("db_connect.php");

    $groupMembers = array_unique($_GET["groupMembers"]);
    $error;
    for ($i = 0; $i < count($groupMembers); $i++) {
        $friend_id = $groupMembers[$i];
        $friendExistsQuery = mysqli_query($db, "SELECT id FROM contacts WHERE user_id = $user_id && friend_id = $friend_id");
        if (!mysqli_num_rows($friendExistsQuery)) {
            $error = true;
            break;
        }
    }
    if (!$error) {
        if ($groupeCreate = mysqli_query($db, "INSERT INTO chats (name) VALUE ('test')")) {
            echo "validated";
        }
    } else {
        echo "error";
    }
}