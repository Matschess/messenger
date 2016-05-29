<?php

$job = $_GET["job"];

include("db_connect.php");

if ($job == "validateGroupName") {
    $groupName = $_GET["groupName"];
    if (validateGroupName($groupName)) {
        if ($groupCreate = mysqli_query($db, "INSERT INTO chats (groupname) VALUE ('$groupName')")) {
            $chat_id = mysqli_insert_id($db);

            // safe new chat_id in SESSION
            session_start();
            $_SESSION["newGroupChatId"] = $chat_id;

            echo "validated";
        } else echo "error";
    } else {
        echo "error";
    }
} elseif ($job == "validateGroupMembers") {
    $toRoot = "../";
    include($toRoot . "variables/user_id.php");

    $error;
    $groupName = $_GET["groupName"];
    if (validateGroupName($groupName)) {
        $groupAdministrators = array_unique($_GET["groupAdministrators"]);
        $groupMembers = array_unique($_GET["groupMembers"]);
        for ($i = 0; $i < count($groupAdministrators); $i++) {
            $friend_id = $groupAdministrators[$i];
            $friendExistsQuery = mysqli_query($db, "SELECT id FROM contacts WHERE user_id = $user_id && friend_id = $friend_id");
            if (!mysqli_num_rows($friendExistsQuery)) {
                $error = true;
                break;
            }
        }
        for ($i = 0; $i < count($groupMembers); $i++) {
            $friend_id = $groupMembers[$i];
            $friendExistsQuery = mysqli_query($db, "SELECT id FROM contacts WHERE user_id = $user_id && friend_id = $friend_id");
            if (!mysqli_num_rows($friendExistsQuery)) {
                $error = true;
                break;
            }
        }
    } else $error = true;

    if (!$error) {
        // get chat_id from SESSION
        session_start();
        if (isset($_SESSION["newGroupChatId"])) {
            $chat_id = $_SESSION["newGroupChatId"];

            if ($addUserToGroup = mysqli_query($db, "INSERT INTO groupmembers (chat_id, user_id, admin) VALUE ($chat_id, $user_id, true)")) {
                for ($i = 0; $i < count($groupAdministrators); $i++) {
                    $friend_id = $groupAdministrators[$i];
                    $addUserToGroup = mysqli_query($db, "INSERT INTO groupmembers (chat_id, user_id, admin) VALUE ($chat_id, $friend_id, true)");
                }
                for ($i = 0; $i < count($groupMembers); $i++) {
                    $friend_id = $groupMembers[$i];
                    $addUserToGroup = mysqli_query($db, "INSERT INTO groupmembers (chat_id, user_id) VALUE ($chat_id, $friend_id)");
                }
                echo "validated";
            }
        }
    } else {
        echo "error";
    }
} elseif ($job == "cancelGroup") {
    // get chat_id from SESSION
    session_start();
    if (isset($_SESSION["newGroupChatId"])) {
        $chat_id = $_SESSION["newGroupChatId"];

        // Old filename
        $select = mysqli_query($db, "SELECT portrait FROM chats WHERE id = '$chat_id'");
        $row = mysqli_fetch_object($select);
        $filenameOld = $row->portrait;

        $error = false;
        if ($filenameOld) {
            $target_dir = "../../data/groupportraits/"; // Upload-Directory for portraits
            if (unlink($target_dir . $filenameOld)) {
                $error = false;
            } else {
                $error = true;
            }
        }

        if (!$error) {
            if (mysqli_query($db, "DELETE FROM chats WHERE id = $chat_id")) {
                echo "canceled";
            } else echo "error";
        }
    } else echo "error";
}

function validateGroupName($groupName)
{
    if ($groupName) {
        if (strlen($groupName) <= 60) {
            return true;
        }
    }
}