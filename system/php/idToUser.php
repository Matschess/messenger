<?php
$toRoot = "../";
include($toRoot . "variables/user_id.php");

include("db_connect.php");

$result = mysqli_query($db, "SELECT username FROM users WHERE id = $user_id");
$row = mysqli_fetch_object($result);
$username = $row->username;
$user = $_POST["user"];

if ($user != $user_id && strtolower($user) != strtolower($username)) {
    $result = mysqli_query($db, "SELECT id, username, firstname, lastname, portrait FROM users WHERE id = '$user' || username = '$user'");
    $row = mysqli_fetch_object($result);
    if ($row) {
        $friend_id = $row->id;
        $alreadyFriendsQuery = mysqli_query($db, "SELECT id FROM contacts WHERE user_id = $user_id && friend_id = $friend_id");
        $alreadyFriendsRow = mysqli_fetch_object($alreadyFriendsQuery);

        // show full name or username
        $firstname = $row->firstname;
        $lastname = $row->lastname;
        $username = $row->username;
        $name = '';
        if ($firstname) {
            $name = $firstname;
            if ($lastname) {
                $name .= " " . $lastname;
            }
        } elseif ($lastname) {
            $name .= $lastname;

        } elseif ($username) {
            $name = $username;
        }

        $portrait = $row->portrait;
        if (!file_exists("../../data/portraits/" . $portrait) || $portrait == "") {
            $portrait = "default.png";
        }
        echo "<table class='tableWide'>";
        echo "<tr>";
        echo "<td class='tdShort'><img src='../data/portraits/$portrait' class='portraitSmall'></img></td>";
        echo "<td id='$friend_id' class='tdLong toProfile'><a href='#' class='link'>$name</a></td>";
        echo "<td><i class='material-icons hover contactCancel tooltip' title='Abbrechen'>close</i></td>";
        echo "<td><i id='$friend_id' class='material-icons hover contactAdd tooltip' title='Als Freund hinzufügen'>done</i></td>";
        echo "</tr>";
        echo "</table>";
    } else {
        echo "error";
    }
} else {
    echo "<i class='material-icons-small'>favorite_border</i> Stimmt, Selbstliebe ist wichtig :D";
}