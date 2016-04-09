<?php
$toRoot = "../";
include($toRoot . "variables/user_id.php");

include("db_connect.php");

$result = mysqli_query($db, "SELECT username FROM users WHERE id = $user_id");
$row = mysqli_fetch_object($result);
$username = $row->username;
$user = $_POST["user"];

if ($user != $user_id && strtolower($user) != strtolower($username)) {
    $result = mysqli_query($db, "SELECT id, username, portrait FROM users WHERE id = '$user' || username = '$user'");
    $row = mysqli_fetch_object($result);
    if ($row) {
        $user_id = $row->id;
        $username = $row->username;
        $portrait = $row->portrait;
        if (!file_exists("../../data/portraits/" . $portrait) || $portrait == "") {
            $portrait = "default.png";
        }
        echo "<table class='tableWide'>";
        echo "<tr>";
        echo "<td class='tdShort'><img src='../data/portraits/$portrait' class='portraitSmall'></img></td>";
        echo "<td id='$user_id' class='tdLong toProfile'><a href='#' class='link'>$username</a></td>";
        echo "<td><i class='material-icons hover contactCancel tooltip' title='Abbrechen'>close</i></td>";
        echo "<td><i id='$user_id' class='material-icons hover contactAdd tooltip' title='Als Freund hinzufügen'>done</i></td>";
        echo "</tr>";
        echo "</table>";
    } else {
        echo "error";
    }
} else {
    echo "<i class='material-icons-small'>favorite_border</i> Stimmt, Selbstliebe ist wichtig :D";
}