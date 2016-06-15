<!DOCTYPE html>
<HTML lang="de">
<HEAD>
    <script src="js/friendsProfile_js.js"></script>
</HEAD>
<BODY>
<div id="Content">
    <?php
    $toRoot = "../";
    include($toRoot . "variables/user_id.php");

    include("db_connect.php");

    if ($_COOKIE["chat_id"]) {
        $chat_id = $_COOKIE["chat_id"];
        $result = mysqli_query($db, "SELECT user_left_id, user_right_id FROM chats WHERE id = $chat_id");
        if($row = mysqli_fetch_object($result)) {
            $user_left_id = $row->user_left_id;
            $user_right_id = $row->user_right_id;
            if($user_left_id == $user_id) {
                $friend_id = $user_right_id;
            }
            else {
                $friend_id = $user_left_id;
            }
        }
    }
    else {
        $friend_id = $_COOKIE["friend_id"];
    }
    $result = mysqli_query($db, "SELECT id, username, firstname, lastname, portrait, statustext, isPublic FROM users WHERE id = '$friend_id'");
    $row = mysqli_fetch_object($result);
    if ($row->isPublic) {
        $visibility = 1;
    }
    $result3 = mysqli_query($db, "SELECT * FROM contacts WHERE user_id = '$friend_id' && friend_id = '$user_id' && NOT accepted");
    $row3 = mysqli_fetch_object($result3);
    if ($row3) {
        $button = "<span id='$friend_id' class='button waitFriends'><i class='material-icons-small'>done</i> Hinzugefügt</span>";
    } else {
        $result2 = mysqli_query($db, "SELECT id FROM contacts WHERE user_id = $user_id && friend_id = $friend_id && NOT accepted");
        $row2 = mysqli_fetch_object($result2);
        $contacts_id = $row2->id;
        if ($row2) {
            $button = "<span id='$contacts_id' class='button acceptFriends'><i class='material-icons-small'>done</i> Freundschaftsanfrage akzeptieren</span>";
        } else {
            $result2 = mysqli_query($db, "SELECT id FROM contacts WHERE ((user_id = '$user_id' && friend_id = '$friend_id') || (user_id = '$friend_id' && friend_id = '$user_id')) && accepted");
            $row2 = mysqli_fetch_object($result2);
            if ($row2) {
                $button = "<span id='$friend_id' class='button endFriends'><i class='material-icons-small'>close</i> Freundschaft beenden</span>";
                $visibility = 1;
            } else {
                $button = "<span id='$friend_id' class='button getFriends'><i class='material-icons-small'>people</i> Freundschaftsanfrage</span>";
            }
        }
    }

    $portrait = $row->portrait;
    $fullPortrait = "../data/portraits/" . $portrait;
    if (!file_exists("../" . $fullPortrait) || $portrait == "" || !$visibility) {
        $fullPortrait = "../data/portraits/default.png";
    }
    $status = $row->statustext;

    // show full name or username
    $firstname = $row->firstname;
    $lastname = $row->lastname;
    $username = $row->username;
    $name = '';
    if($firstname) {
        $name = $firstname;
        if($lastname) {
            $name .= " " . $lastname;
        }
    }
    elseif($lastname) {
        $name .= $lastname;

    }
    elseif($username) {
        $name = $username;
    }
    else {
        $name = "?";
    }

    echo "<div id='portrait'>";
    echo "<img src='$fullPortrait' id='portraitImage'/>";
    echo "</div>";
    echo "<div id='portraitBackground'>";
    echo "<br/>";
    echo "<span id='profileText'>$name</span>";
    echo "<br/>";
    if ($visibility && $username) {
        echo "<span id='userID' class='tooltip' title='Die ID von $name'>$username</span>";
    }
    echo "<br/>";
    echo "<br/>";
    if ($status && $visibility) {
        echo "<i class='material-icons color_grey'>format_quote</i>";
        echo "<span id='myStatus' class='status'>$status</span>";
        echo "<input type='text' id='myStatusEdit' placeholder='Status...' value='$status'/>";
    }

    echo "<br/>";
    echo $button;
    ?>
</div>
</div>
<i id="backToChat" class="material-icons hover back">arrow_back</i>
</BODY>
</HTML>