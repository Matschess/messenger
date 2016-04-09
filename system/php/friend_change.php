<?php
$toRoot = "../";
include($toRoot . "variables/user_id.php");

include("db_connect.php");

$job = $_POST["job"];
$friend_id = $_POST["friend_id"];

if ($job == "add") {
    $result = mysqli_query($db, "SELECT id FROM contacts WHERE user_id = '$user_id' && friend_id = '$friend_id' && NOT accepted");
    $row = mysqli_fetch_object($result);
    $id = $row->id;
    if ($id) {
        $update = mysqli_query($db, "UPDATE contacts SET accepted = 1 WHERE id = '$id'");
        if ($update) {
            echo "updated";
        } else {
            echo "error";
        }
    } else {
        $result = mysqli_query($db, "SELECT id FROM contacts WHERE user_id = '$friend_id' && friend_id = '$user_id'");
        $row = mysqli_fetch_object($result);
        if (!$row) {
            $result = mysqli_query($db, "SELECT username FROM users WHERE id = $user_id");
            $row = mysqli_fetch_object($result);
            $friend = $row->username;
            $insert = mysqli_query($db, "INSERT INTO contacts (user_id, friend_id, friend) VALUES ($friend_id, $user_id, '$friend')");
            if ($insert) {
                echo "added";
            } else {
                echo "error";
            }
        } else {
            echo "already";
        }
    }

} elseif ($job == "remove") {
    $delete = mysqli_query($db, "DELETE FROM contacts WHERE (user_id = '$user_id' && friend_id = '$friend_id') || (user_id = '$friend_id' && friend_id = '$user_id')");
    if ($delete) {
        echo "deleted";
    } else {
        echo "error";
    }
}
?>