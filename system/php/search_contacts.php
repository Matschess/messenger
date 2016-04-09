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

$friend = $_POST["friend"];

if ($friend == "") {
    $friend = "%" . $friend;
}

$search = mysqli_query($db, "SELECT friend_id FROM contacts WHERE user_id = '$user_id' && friend LIKE '$friend%' && accepted ORDER BY friend");
while ($row = mysqli_fetch_object($search)) {
    $friend_id = $row->friend_id;
    $result2 = mysqli_query($db, "SELECT username, online, portrait, statustext FROM users WHERE user_id = '$friend_id'");
    $row2 = mysqli_fetch_object($result2);
    $newCurrentLetter = strtoupper(substr($row2->username, 0, 1));
    if ($newCurrentLetter != $currentLetter) {
        $currentLetter = $newCurrentLetter;
        echo "<div class='currentLetter'>$currentLetter</div>";
    }
    $online = $row2->online;
    $datetime1 = date_create($online);
    $datetime2 = date_create(date('y-m-d H:i:s', time()));
    $interval = $datetime2->getTimestamp() - $datetime1->getTimestamp();
    if ($interval <= $maxLastSeen * 60) {
        $online = "contactOnline";
    } else {
        $online = "contactOffline";
    }
    $portrait = $row2->portrait;
    $statustext = $row2->statustext;
    if (!file_exists("../../data/portraits/" . $portrait) || $portrait == "") {
        $portrait = "default.png";
    }
    echo "<div id='$friend_id' class='contact ripple $online'>";
    echo "<img src='../data/portraits/$portrait' id='$friend_id' class='img_round toProfile' style='margin-right: 10px;'></img>";
    echo "<div class='contactInfo'>";
    echo $row2->username;
    echo "<div class='contactStatus'>$statustext</div>";
    echo "</div>";
    $datetime3 = date_create(date('y-m-d', time()));
    $seconsToday = $datetime2->getTimestamp() - $datetime3->getTimestamp(); // Seconds from 00:00:00
    if ($interval <= $maxLastSeen * 60) {
        $userstatus = "Online";
    } elseif ($interval <= $seconsToday) { // Seconds everyday
        $userstatus = date_format($datetime1, 'H:i');
    } elseif ($interval <= 172800) { // Seconds of two days
        $userstatus = "GESTERN " . date_format($datetime1, 'H:i');
    } elseif ($interval <= 604800) { // Seconds of every week
        setlocale(LC_TIME, 'German_Austria');
        $userstatus = strftime('%a', $datetime1->getTimestamp()) . " " . date_format($datetime1, 'H:i');
    } else {
        $userstatus = date_format($datetime1, 'd.m.Y') . " " . date_format($datetime1, 'H:i');
    }
    echo "<span class='contactTime'>$userstatus</span>";
    echo "</div>";
}
?>

</BODY>
</HTML>