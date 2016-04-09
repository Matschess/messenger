<!DOCTYPE html>
<HTML lang="de">
<HEAD>
    <meta charset="utf-8">
    <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
    <script src="js/chat_js.js"></script>
    <script src="plugins/emojify/emojify.js"></script>
    <script src="plugins/color-thief-master/src/color-thief.js"></script>
</HEAD>
<BODY>
<div id="chat">
    <?php
    $toRoot = "../";
    include($toRoot . "variables/user_id.php");

    include("db_connect.php");

    $maxOnline = 100; // maximum seconds since last registered on server

    if ($_GET["friend_id"]) {
        $friend_id = $_GET["friend_id"];
    } elseif (isset($_COOKIE["chat_id"])) {
        $chat_id = $_COOKIE["chat_id"];
        $chatExistsQuery = mysqli_query($db, "SELECT user_left_id, user_right_id FROM chats WHERE chat_id = $chat_id");
        if (mysqli_num_rows($ChatExistsQuery)) {
            $chatExistsRows = mysqli_fetch_object($chatExistsQuery);
            $user_left_id = $chatExistsRows->user_left_id;
            $user_right_id = $chatExistsRows->user_right_id;
            if ($user_left_id == $user_id) {
                $friend_id = $user_right_id;
            } else $friend_id = $user_left_id;
        }
    }
    $friendQuery = mysqli_query($db, "SELECT username, portrait, color, last_seen FROM users WHERE id = '$friend_id'");
    if (mysqli_num_rows($friendQuery)) {
    $friendRows = mysqli_fetch_object($friendQuery);
    $friend_name = $friendRows->username;

    // Portrait
    $portrait = $friendRows->portrait;
    if (!file_exists("../../data/portraits/" . $portrait) || $portrait == "") {
        $portrait = "default.png";
    }


    $color = $friendRows->color;

    setcookie("messengerCurrentChatColor", $color, 0, '/'); // Set cookie until browser is closed

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
        $onlineStatus = "um " . date_format($datetimeUser, 'H:i');
    } elseif ($timeDifference <= 172800) { // Seconds of two days
        $onlineStatus = "gestern " . "um " . date_format($datetimeUser, 'H:i');
    } elseif ($timeDifference <= 604800) { // Seconds of every week
        setlocale(LC_TIME, 'German_Austria');
        $onlineStatus = "am " . strftime('%A', $datetimeUser->getTimestamp()) . " um " . date_format($datetimeUser, 'H:i');
    } else {
        $onlineStatus = "am " . date_format($datetimeUser, 'd.m.Y') . " um " . date_format($datetimeUser, 'H:i');
    }


    $ChatExistsQuery = mysqli_query($db, "SELECT id FROM chats WHERE user_left_id = $user_id && user_right_id = $friend_id || user_left_id = $friend_id && user_right_id = $user_id");
    if (mysqli_num_rows($ChatExistsQuery)) {
        $ChatExistsRows = mysqli_fetch_object($ChatExistsQuery);
        $chat_id = $ChatExistsRows->id;

        // Set chat as current chat
        setcookie("chat_id", $chat_id, 0, '/');
    } else {
        echo "error";
    }

    ?>

    <div id="chatInfo">
        <?php
        echo "<span id='$friend_id' class='toProfile'><img src='../data/portraits/$portrait' id='imgForBackground' class='img_round_flat' style='margin-right: 10px;'/></span>";
        ?>
        <div id="userInfo">
            <?php
            echo $friend_name;

            echo "<div id='userstatus'>zuletzt online $onlineStatus</div>";
            ?>
        </div>
        <div id="options">
            <div id="optionsIcon">
                <i class="material-icons hover" style="margin-right: 10px">more_vert</i>
            </div>
            <div id="deleteChat" class="option">
                Chatverlauf löschen
            </div>
            <div id="endFriends" class="option">
                In Gruppe konvertieren
            </div>
        </div>
    </div>
    <div id="content">
        <?php
        echo "Kokl";
        if ($friend_id) {
            echo $friend_id;
            $ChatExistsQuery = mysqli_query($db, "SELECT id FROM chats WHERE user_left_id = $user_id && user_right_id = $friend_id || user_left_id = $friend_id && user_right_id = $user_id");
            if (mysqli_num_rows($ChatExistsQuery)) {
                $ChatExistsRows = mysqli_fetch_object($ChatExistsQuery);
                $chat_id = $ChatExistsRows->id;

                // Set chat as current chat
                setcookie("chat_id", $chat_id, 0, '/');
            }
        } elseif ($_COOKIE["chat_id"]) {
            $chat_id = $_COOKIE["chat_id"];
            echo $chat_id;
        } else {
            echo "Kokl";
        }

        $messagesQuery = mysqli_query($db, "SELECT user_id, message, sent FROM messages WHERE chat_id = $chat_id");
        if (mysqli_num_rows($messagesQuery)) {
            $lastuser_id;
            while ($messagesRows = mysqli_fetch_object($messagesQuery)) {
                $speaker_id = $messagesRows->user_id;
                $message = $messagesRows->message;
                $sent = date_create($messagesRows->sent);
                $sentFormatted = date_format($sent, 'H:i');
                if ($speaker_id == $user_id) {
                    echo "<div class='chatRight'>";
                    $myPortrait = $_COOKIE['messengerUserPortrait'];
                    if (!file_exists("../../data/portraits/" . $myPortrait) || $myPortrait == "") {
                        $myPortrait = "default.png";
                    }
                    if ($lastuser_id != $speaker_id) {
                        echo "<div class='bubble'>";
                    } else {
                        echo "<div class='bubbleManuallyRight'>";
                    }
                    echo $message;
                    echo "<span class='time'>$sentFormatted</span>";
                    echo "<i class='material-icons-small doneAll'>done_all</i>";
                    echo "</div>";
                    if ($lastuser != $speaker_id) {
                        echo "<img src='../data/portraits/$myPortrait' class='img_round' style='margin-left: 10px;'/>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class='chatLeft'>";
                    if ($lastuser != $speaker_id) {
                        echo "<img src='../data/portraits/$portrait' class='img_round' style='margin-right: 10px;'/>";
                        echo "<div class='bubble'>";
                    } else {
                        echo "<div class='bubbleManuallyLeft'>";
                    }
                    echo $message;
                    echo "<span class='time'>$sentFormatted</span>";
                    echo "</div>";
                    echo "</div>";
                }
                $lastuser = $speaker_id;
            }
        } else {
            echo "<div id='noMessages'><i class='material-icons'>chat_bubble_outline</i> Sag Hallo und starte eine Konversation</div>";
        }

        } else {
            echo "<div id='noMessages'><i class='material-icons'>chat_bubble_outline</i> Sag Hallo und starte eine Konversation</div>";
        }
        ?>

    </div>
    <div id="chatInput">
        <div id="smileyChooser">
            <div id="smileyChooserToPeople" class="tab">
                <i class="material-icons-large">mood</i>
            </div>
            <div id="smileyChooserToNature" class="tab">
                <i class="material-icons-large">nature</i>
            </div>
            <div id="smileyChooserToThings" class="tab">
                <i class="material-icons-large">notifications</i>
            </div>
            <div id="smileyChooserToVehicles" class="tab">
                <i class="material-icons-large">directions_car</i>
            </div>
            <div id="smileyChooserToSigns" class="tab">
                <i class="material-icons-large">message</i>
            </div>

            <div id="smileyChooserContainer"></div>
        </div>
        <div id="attacher">
            Dateien anfügen
        </div>
        <div class="chatTextBox" contenteditable="true" placeholder="Tippe eine Nachricht">&nbsp;</div>
        <i id="smiley" class="material-icons hover tooltip">mood</i>
        <i id="attach" class="material-icons hover">attach_file</i>
        <i class="material-icons" style="margin-left: 20px;">send</i>
        <?php
        /*
        ?>
        else {
            echo "<div id='noChatActive'><i class='material-icons'>chat_bubble_outline</i> Wähle einen Kontakt aus, um eine Konversation zu beginnen</div>";
        }*/
        ?>
    </div>
</div>
</BODY>
</HTML>

<script>
    $(document).ready(function () {
        $color = $.cookie('messengerCurrentChatColor');
        if ($('#chatInfo').length) {
            if ($color) {
                $('#containerRight .tableNavigation td, #chatInfo').css('background-color', $color);
                $('#chat .doneAll').css('color', $color);
            }
            else {
                var sourceImage = document.getElementById("imgForBackground");
                var colorThief = new ColorThief();
                $color = colorThief.getColor(sourceImage);
                $('#containerRight .tableNavigation td, #chatInfo').css('background-color', 'rgb(' + $color + ')');
                $('#chat .doneAll').css('color', 'rgb(' + $color + ')');
            }
        }


        emojify.setConfig({
            img_dir: 'plugins/emojify/images/emoji'  // Directory for emoji images

        });

        emojify.run();
    });
</script>