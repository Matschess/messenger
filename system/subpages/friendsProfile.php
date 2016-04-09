<!DOCTYPE html>
<HTML lang="de">
<HEAD>
    <link rel="stylesheet" href="css/profile_style.css">
    <script src="js/friendsProfile_js.js"></script>
</HEAD>
<BODY>
<div id="Content">
    <?php
    $toRoot = "../";
    include($toRoot . "variables/user_id.php");

    include("db_connect.php");

    $friend_id = $_GET["friend_id"];
    $result = mysqli_query($db, "SELECT id, username, firstname, lastname, portrait, statustext, isPublic FROM users WHERE id = '$friend_id'");
    $row = mysqli_fetch_object($result);
    if ($row->isPublic) {
        $visibility = 1;
    }
    $result3 = mysqli_query($db, "SELECT * FROM contacts WHERE user_id = '$friend_id' && friend_id = '$user_id' && NOT accepted");
    $row3 = mysqli_fetch_object($result3);
    if ($row3) {
        echo "<div id='$friend_id' class='button waitFriends'><i class='material-icons-small'>done</i> Hinzugefügt</div>";
    } else {
        $result2 = mysqli_query($db, "SELECT id FROM contacts WHERE user_id = $user_id && friend_id = $friend_id && NOT accepted");
        $row2 = mysqli_fetch_object($result2);
        $contacts_id = $row2->id;
        if ($row2) {
            echo "<div id='$contacts_id' class='button acceptFriends'><i class='material-icons-small'>done</i> Freundschaftsanfrage akzeptieren</div>";
        } else {
            $result2 = mysqli_query($db, "SELECT id FROM contacts WHERE ((user_id = '$user_id' && friend_id = '$friend_id') || (user_id = '$friend_id' && friend_id = '$user_id')) && accepted");
            $row2 = mysqli_fetch_object($result2);
            if ($row2) {
                echo "<div id='$friend_id' class='button endFriends'><i class='material-icons-small'>close</i> Freundschaft beenden</div>";
                $visibility = 1;
            } else {
                echo "<div id='$friend_id' class='button getFriends'><i class='material-icons-small'>people</i> Freundschaftsanfrage</div>";
            }
        }
    }

    $portrait = $row->portrait;
    $fullPortrait = "../data/portraits/" . $portrait;
    if (!file_exists("../" . $fullPortrait) || $portrait == "" || !$visibility) {
        $fullPortrait = "../data/portraits/default.png";
    }
    $status = $row->statustext;
    $username = $row->username;

    $firstname = $row->firstname;
    $lastname = $row->lastname;
    echo "<div id='portrait'>";
    echo "<img src='$fullPortrait' id='portraitImage'/>";
    echo "</div>";
    echo "<div id='portraitBackground'>";
    echo "<br/>";
    echo "<span id='profileText'>$firstname $lastname</span>";
    echo "<br/>";
    if ($visibility) {
        echo "<span id='userID' class='tooltip' title='Die ID'>$username</span>";
    }
    echo "<br/>";
    echo "<br/>";
    if ($status && $visibility) {
        echo "<i class='material-icons color_grey'>format_quote</i>";
        echo "<span id='myStatus' class='status'>$status</span>";
        echo "<input type='text' id='myStatusEdit' placeholder='Status...' value='$status'/>";
    }
    ?>
</div>

</BODY>
</HTML>