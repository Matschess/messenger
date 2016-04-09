<?php
$toRoot = "../";
include($toRoot . "variables/user_id.php");

include("db_connect.php");

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