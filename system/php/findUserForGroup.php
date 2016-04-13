<?php
$toRoot = "../";
include($toRoot . "variables/user_id.php");

include("db_connect.php");

$job = $_POST["job"];

if ($job == "search") {
    $search = $_POST["search"];
    $users = array();

    $result = mysqli_query($db, "SELECT users.id, users.username, users.portrait FROM users LEFT JOIN contacts ON contacts.friend_id = users.id WHERE contacts.user_id = $user_id && contacts.friend like '$search%'");
    while ($row = mysqli_fetch_object($result)) {
        $user_id = $row->id;
        $username = $row->username;

        // Portrait
        $portrait = $row->portrait;
        $fullPortrait = "../data/portraits/" . $portrait;
        if (!file_exists("../" . $fullPortrait) || $portrait == "") {
            $fullPortrait = "../data/portraits/default.png";
        }

        array_push($users, array($user_id, $username, $fullPortrait));
    }
    echo json_encode($users);
} elseif ($job == "validate") {
    $friend = $_POST["friend"];
    $result = mysqli_query($db, "SELECT users.id, users.username, users.portrait FROM users LEFT JOIN contacts ON contacts.friend_id = users.id WHERE contacts.user_id = $user_id && (contacts.friend_id = $friend || contacts.friend = '$friend')");
    $row = mysqli_fetch_object($result);
    if ($row) {
        $user_id = $row->id;
        $username = $row->username;

        // Portrait
        $portrait = $row->portrait;
        $fullPortrait = "../data/portraits/" . $portrait;
        if (!file_exists("../" . $fullPortrait) || $portrait == "") {
            $fullPortrait = "../data/portraits/default.png";
        }
        echo json_encode(array($user_id, $username, $fullPortrait));
    } else {
        echo "error";
    }
}
?>