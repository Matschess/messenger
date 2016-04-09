<!DOCTYPE html>
<HTML lang="de">
<HEAD>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/contacts_style.css">
    <script src="js/contacts_js.js"></script>
</HEAD>
<BODY>
<?php
$toRoot = "../";
include($toRoot . "variables/user_id.php");

include("db_connect.php");

$maxOnline = 100; // maximum seconds since last registered on server

$searchTag = $_GET["searchTag"];

$currentLetter;
if($searchTag) $contactsQuery = mysqli_query($db, "SELECT friend_id FROM contacts WHERE user_id = $user_id && friend like '$searchTag%' && accepted ORDER BY friend");
else $contactsQuery = mysqli_query($db, "SELECT friend_id FROM contacts WHERE user_id = $user_id && accepted ORDER BY friend");
if (mysqli_num_rows($contactsQuery)) {
    if(!$searchTag) {
        echo "<div id='createGroup' class='ripple'>";
        echo "<i class='material-icons'>people</i>";
        echo "<span>Neue Gruppe</span>";
        echo "</div>";
    }
    while($contactsRows = mysqli_fetch_object($contactsQuery)) {
        $friend_id = $contactsRows->friend_id;
        $friendQuery = mysqli_query($db, "SELECT username, portrait, statustext, last_seen FROM users WHERE id = '$friend_id'");
        $friendRows = mysqli_fetch_object($friendQuery);
        $newCurrentLetter = strtoupper(substr($friendRows->username, 0, 1));
        if ($newCurrentLetter != $currentLetter) {
            $currentLetter = $newCurrentLetter;
            echo "<div class='currentLetter'>$currentLetter</div>";
        }

        $friend_name = $friendRows->username;

        // Portrait
        $portrait = $friendRows->portrait;
        if (!file_exists("../../data/portraits/" . $portrait) || $portrait == "") {
            $portrait = "default.png";
        }

        $statustext = $friendRows->statustext;

        // Online-time
        $last_seen = $friendRows->last_seen;
        $datetimeUser = date_create($last_seen);
        $datetimeCurrent = date_create(date('y-m-d H:i:s', time()));
        $timeDifference = $datetimeCurrent->getTimestamp() - $datetimeUser->getTimestamp();
        $dateCurrent = date_create(date('y-m-d', time()));
        $secondsToday = $datetimeCurrent->getTimestamp() - $dateCurrent->getTimestamp(); // Seconds from 00:00:00
        if ($timeDifference <= $maxOnline) {
            $onlineStatus = "Online";
        } elseif ($timeDifference <= $secondsToday) { // Seconds everyday
            $onlineStatus = date_format($datetimeUser, 'H:i');
        } elseif ($timeDifference <= 172800) { // Seconds of two days
            $onlineStatus = "GESTERN " . date_format($datetimeUser, 'H:i');
        } elseif ($timeDifference <= 604800) { // Seconds of every week
            setlocale(LC_TIME, 'German_Austria');
            $onlineStatus = strftime('%a', $datetimeUser->getTimestamp()) . " " . date_format($datetimeUser, 'H:i');
        } else {
            $onlineStatus = date_format($datetimeUser, 'd.m.Y') . " " . date_format($datetimeUser, 'H:i');
        }

        echo "<div id='$friend_id' class='contact ripple'>";
        echo "<img src='../data/portraits/$portrait' id='$friend_id' class='img_round img_margin_right toProfile'></img>";
        echo "<div class='contactInfo'>";
        echo $friend_name;
        echo "<div class='contactStatus'>$statustext</div>";
        echo "</div>";
        echo "<span class='contactTime'>$onlineStatus</span>";
        echo "</div>";
    }
} else {
    if($searchTag) echo "<div id='addFirstFriend'><i class='material-icons'>do_not_disturb</i> Keinen Freund gefunden</div>";
    else echo "<div id='addFirstFriend'><i class='material-icons'>people</i> Füge deine ersten Freunde hinzu</div>";
}
?>
</BODY>
</HTML>