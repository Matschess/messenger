<?php
$toRoot = "../";
include($toRoot . "variables/user_id.php");

include("db_connect.php");

if(isset($_GET["job"])) {
    $job = $_GET["job"];

    if($job == "groupPortrait") {
        $chat_id = $_COOKIE["chat_id"];
        $portraitDir = "../../data/groupPortraits/"; // Directory of the portraits

        $currentPortraitQuery = mysqli_query($db, "SELECT portrait FROM chats WHERE id = $chat_id");
        if (mysqli_num_rows($currentPortraitQuery)) {
            $currentPortraitRow = mysqli_fetch_object($currentPortraitQuery);
            $currentPortrait = $currentPortraitRow->portrait;
            if (unlink($portraitDir . $currentPortrait)) {
                $clearPortraitQuery = mysqli_query($db, "UPDATE chats SET portrait = '' WHERE id = $chat_id");
                if ($clearPortraitQuery) echo "deleted";
                else echo "error";
            } else echo "error";
        } else echo "error";
    }
}
else {
    $portraitDir = "../../data/portraits/"; // Directory of the portraits

    $currentPortraitQuery = mysqli_query($db, "SELECT portrait FROM users WHERE id = $user_id");
    if (mysqli_num_rows($currentPortraitQuery)) {
        $currentPortraitRow = mysqli_fetch_object($currentPortraitQuery);
        $currentPortrait = $currentPortraitRow->portrait;
        if (unlink($portraitDir . $currentPortrait)) {
            $clearPortraitQuery = mysqli_query($db, "UPDATE users SET portrait = '' WHERE id = $user_id");
            if ($clearPortraitQuery) echo "deleted";
            else echo "error";
        } else echo "error";
    } else echo "error";
}