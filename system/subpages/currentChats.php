<!DOCTYPE html>
<HTML lang="de">
<HEAD>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/contacts_style.css">
    <script src="plugins/emojify/emojify.js"></script>
    <script src="js/contacts_js.js"></script>
</HEAD>
<BODY>
<?php
$toRoot = "../";
include($toRoot . "variables/user_id.php");

include("db_connect.php");

$maxOnline = 100; // maximum seconds since last registered on server

$friend_id;
$currentLetter;
$contactsQuery = mysqli_query($db, "SELECT id, user_left_id, user_right_id, last_active FROM chats WHERE user_left_id = $user_id || user_right_id = $user_id ORDER BY last_active");
if (mysqli_num_rows($contactsQuery)) {
    while ($contactsRows = mysqli_fetch_object($contactsQuery)) {
        $chat_id = $contactsRows->id;
        $user_left_id = $contactsRows->user_left_id;
        $user_right_id = $contactsRows->user_right_id;
        $last_active = $contactsRows->last_active;
        if($user_left_id == $user_id) {
            $friend_id = $user_right_id;
        }
        else $friend_id = $user_left_id;
        $friendQuery = mysqli_query($db, "SELECT username, portrait FROM users WHERE id = '$friend_id'");
        $friendRows = mysqli_fetch_object($friendQuery);

        $friend_name = $friendRows->username;

        // Portrait
        $portrait = $friendRows->portrait;
        if (!file_exists("../../data/portraits/" . $portrait) || $portrait == "") {
            $portrait = "default.png";
        }

        $messagesQuery = mysqli_query($db, "SELECT user_id, message, sent FROM messages WHERE chat_id = $chat_id && sent = '$last_active' ORDER BY sent");
        if (mysqli_num_rows($messagesQuery)) {
            $messagesRows = mysqli_fetch_object($messagesQuery);
            $last_message_user_id = $messagesRows->user_id;
            $message = $messagesRows->message;
            $sent = $messagesRows->sent;
            // Last message sent
            $sent = date_create($sent);
            $sent = date_format($sent, 'H:i');

            if(strlen($message) > 43) {
                $message = $s = substr($message, 0, 40) . "..."; // cut to long message
            }
            if($last_message_user_id == $user_id) $last_message = $message . " " . "<span class='contactLastMessageSent'>$sent <i class='material-icons-tiny doneAll'>done_all</i></span>";
            else  $last_message = $message . " " . "<span class='contactLastMessageSent'>$sent</span>";
        }
        else {
            $last_message = "error";
        }

        echo "<div id='$friend_id' class='contact ripple'>";
        echo "<img src='../data/portraits/$portrait' id='$friend_id' class='img_round img_margin_right toProfile'></img>";
        echo "<div class='contactInfo'>";
        echo $friend_name;
        echo "<div class='contactLastMessage'>$last_message</div>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<div id='addFirstFriend'><i class='material-icons'>chat_bubble_outline</i> Beginne mit deiner ersten Konversation</div>";
}
?>
</BODY>
</HTML>


<script>
    $(document).ready(function () {


        emojify.setConfig({
            img_dir: 'plugins/emojify/images/emoji'  // Directory for emoji images

        });

        emojify.run();
    });
</script>