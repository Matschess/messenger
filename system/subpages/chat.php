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
    $isGroup;

    if ($_GET["chat_id"]) {
        $chat_id = $_GET["chat_id"];
        $chatExistsQuery = mysqli_query($db, "SELECT user_left_id, user_right_id FROM chats WHERE id = $chat_id");
        if (mysqli_num_rows($chatExistsQuery)) {
            $chatExistsRows = mysqli_fetch_object($chatExistsQuery);
            $user_left_id = $chatExistsRows->user_left_id;
            $user_right_id = $chatExistsRows->user_right_id;
            if ($user_left_id && $user_right_id) {
                if ($user_left_id == $user_id) {
                    $friend_id = $user_right_id;
                } else $friend_id = $user_left_id;
            } else {
                $isGroup = true;
            }
        }
    } elseif ($_GET["friend_id"]) {
        $friend_id = $_GET["friend_id"];
    } elseif (isset($_COOKIE["chat_id"])) {
        $chat_id = $_COOKIE["chat_id"];
        $chatExistsQuery = mysqli_query($db, "SELECT user_left_id, user_right_id FROM chats WHERE id = $chat_id");
        if (mysqli_num_rows($chatExistsQuery)) {
            $chatExistsRows = mysqli_fetch_object($chatExistsQuery);
            $user_left_id = $chatExistsRows->user_left_id;
            $user_right_id = $chatExistsRows->user_right_id;
            if ($user_left_id && $user_right_id) {
                if ($user_left_id == $user_id) {
                    $friend_id = $user_right_id;
                } else $friend_id = $user_left_id;
            } else {
                $isGroup = true;
            }
        }
    } elseif (isset($_COOKIE["friend_id"])) {
        $friend_id = $_COOKIE["friend_id"];
    }

    if (!$isGroup) {
        $isFriend = mysqli_query($db, "SELECT id FROM contacts WHERE user_id = $user_id && friend_id = $friend_id && accepted");
        if (mysqli_num_rows($isFriend)) {
            $friendQuery = mysqli_query($db, "SELECT username, firstname, lastname, portrait, color, last_seen FROM users WHERE id = '$friend_id'");
            if (mysqli_num_rows($friendQuery)) {
                $friendRows = mysqli_fetch_object($friendQuery);

                // show full name or username
                $firstname = $friendRows->firstname;
                $lastname = $friendRows->lastname;
                $username = $friendRows->username;
                $friend_name = '';
                if ($firstname) {
                    $friend_name = $firstname;
                    if ($lastname) {
                        $friend_name .= " " . $lastname;
                    }
                } elseif ($lastname) {
                    $friend_name .= $lastname;

                } elseif ($username) {
                    $friend_name = $username;
                } else {
                    $friend_name = "?";
                }

                // Portrait
                $portrait = $friendRows->portrait;
                if (!file_exists("../../data/portraits/" . $portrait) || $portrait == "") {
                    $portrait = "portraits/default.png";
                } else {
                    $portrait = "portraits/" . $portrait;
                }

                $color = $friendRows->color;

                setcookie("messengerColor", $color, 0, '/'); // Set cookie until browser is closed

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
                    $onlineStatus = "zuletzt online um " . date_format($datetimeUser, 'H:i');
                } elseif ($timeDifference <= 172800) { // Seconds of two days
                    $onlineStatus = "zuletzt online gestern " . "um " . date_format($datetimeUser, 'H:i');
                } elseif ($timeDifference <= 604800) { // Seconds of every week
                    setlocale(LC_TIME, 'German_Austria');
                    $onlineStatus = "zuletzt online am " . strftime('%A', $datetimeUser->getTimestamp()) . " um " . date_format($datetimeUser, 'H:i');
                } else {
                    $onlineStatus = "zuletzt online am " . date_format($datetimeUser, 'd.m.Y') . " um " . date_format($datetimeUser, 'H:i');
                }
            }
        }
    } else {
        $getGroupMembers = mysqli_query($db, "SELECT users.id, users.username, users.firstname, users.lastname FROM users LEFT JOIN groupmembers ON groupmembers.user_id = users.id WHERE groupmembers.chat_id = $chat_id ORDER BY users.firstname, users.lastname LIMIT 6");
        if (mysqli_num_rows($getGroupMembers)) {
            $members = [];
            while ($groupMembersRows = mysqli_fetch_object($getGroupMembers)) {
                $member_id = $groupMembersRows->id;
                $memberFirstname = $groupMembersRows->firstname;
                $memberLastname = $groupMembersRows->lastname;
                // show full name or username
                $firstname = $groupMembersRows->firstname;
                $lastname = $groupMembersRows->lastname;
                $username = $groupMembersRows->username;
                $member_name = '';
                if ($firstname) {
                    $member_name = $firstname;
                    if ($lastname) {
                        $member_name .= " " . $lastname;
                    }
                } elseif ($lastname) {
                    $member_name .= $lastname;

                } elseif ($username) {
                    $member_name = $username;
                } else {
                    $member_name = "?";
                }
                $members[] = "<span class='linkToMember' id='linkTo" . $member_id . "'>" . $member_name . "</span>";
            }
        }

        $groupQuery = mysqli_query($db, "SELECT groupname, portrait, color FROM chats WHERE id = $chat_id");
        if (mysqli_num_rows($groupQuery)) {
            $groupRows = mysqli_fetch_object($groupQuery);

            // show full name or username
            $groupname = $groupRows->groupname;
            if ($groupname) {
                $friend_name = $groupname;
            } else {
                $friend_name = "?";
            }

            // Portrait
            $portrait = $groupRows->portrait;
            if (!$portrait || !file_exists("../../data/groupportraits/" . $portrait) || $portrait == "") {
                $portrait = "portraits/default.png";
            } else {
                $portrait = "groupportraits/" . $portrait;
            }

            $color = $groupRows->color;

            setcookie("messengerColor", $color, 0, '/'); // Set cookie until browser is closed

            $groupMembers = $members[0];
            $countMembers = count($members);
            if ($countMembers > 5) {
                for ($i = 1; $i < 5; $i++) {
                    $groupMembers .= ", " . $members[$i];
                }
                $groupMembers .= ", ...";
            } else {
                for ($i = 1; $i < $countMembers; $i++) {
                    $groupMembers .= ", " . $members[$i];
                }
            }
        }
    }

    if (!$isGroup) {
        $ChatExistsQuery = mysqli_query($db, "SELECT id FROM chats WHERE user_left_id = $user_id && user_right_id = $friend_id || user_left_id = $friend_id && user_right_id = $user_id");
        if (mysqli_num_rows($ChatExistsQuery)) {
            $ChatExistsRows = mysqli_fetch_object($ChatExistsQuery);
            $chat_id = $ChatExistsRows->id;

            // clear other cookie to avoid if-crash
            unset($_COOKIE['friend_id']);
            setcookie('friend_id', 0, time() - 3600, '/');
            // Set chat as current chat
            setcookie("chat_id", $chat_id, 0, '/');
        } else {
            // clear other cookie to avoid if-crash
            unset($_COOKIE['chat_id']);
            setcookie('chat_id', 0, time() - 3600, '/');
            // Set friend as current chatpartner
            setcookie("friend_id", $friend_id, 0, '/');
        }
    } else {
        unset($_COOKIE['friend_id']);
        setcookie('friend_id', 0, time() - 3600, '/');
        // Set chat as current chat
        setcookie("chat_id", $chat_id, 0, '/');
    }
    ?>

    <div id="chatInfo">
        <?php
        if ($isGroup) {
            $link = "moreGroupMembers";
        } else {
            $link = "toProfile";
        }
        echo "<i id='backToOverview' class='material-icons hover back'>arrow_back</i>";
        echo "<span id='$friend_id' class='$link'><img src='../data/$portrait' id='imgForBackground' class='img_round_flat' style='margin-right: 10px;'/></span>";
        ?>
        <div id="userInfo">
            <?php
            echo $friend_name;

            if ($onlineStatus) {
                echo "<div id='userstatus'>$onlineStatus</div>";
            } else {
                echo "<div id='userstatus'>$groupMembers <span id='moreGroupMembersBar' class='moreGroupMembers'>Mehr</span></div>";
            }
            echo "<div id='typing'></div>";
            ?>
        </div>
        <div id="options">
            <div id="optionsIcon">
                <i class="material-icons hover" style="margin-right: 10px">more_vert</i>
            </div>
            <div id="deleteChat" class="option">
                Chatverlauf löschen
            </div>
            <div id="chatToMedia" class="option">
                Medien
            </div>
        </div>
    </div>
    <div id="content">
        <?php
        $messagesQuery = mysqli_query($db, "SELECT NULL as id, user_id, message, NULL as isMedia, sent, isRead FROM messages WHERE chat_id = $chat_id UNION SELECT id, user_id, dataname, datatype, sent, isRead FROM media WHERE chat_id = $chat_id ORDER BY sent");
        if (mysqli_num_rows($messagesQuery)) {
            $lastuser;
            while ($messagesRows = mysqli_fetch_object($messagesQuery)) {
                $speaker_id = $messagesRows->user_id;
                $message = $messagesRows->message;
                $isMedia = $messagesRows->isMedia;
                if ($isMedia) {
                    $path = "../data/media/" . $chat_id . "/";
                    if ($isMedia == "mp4") {
                        $message = "<div class='mediaVideo'><div class='videoControls'><div class='videoPlayButton'><i class='material-icons'>play_arrow</i></div></div><video preload='metadata'><source src='" . $path . $message . "." . $isMedia . "' type='video/mp4'>Your browser does not support the video tag.</video></div>";
                    } elseif ($isMedia == "mp3") {
                        $message = "<div class='mediaAudio'><div class='audioControls'><div class='audioPlayButton'><i class='material-icons'>play_arrow</i></div></div><audio preload='metadata'><source src='" . $path . $message . "." . $isMedia . "' type='audio/mp3'>Your browser does not support the video tag.</audio></div>";
                    } elseif ($isMedia == "docx") {
                        $media_id = $messagesRows->id;
                        $message = "<a href='php/download_media.php?media_id=$media_id'><img class='mediaDocumentThn' src='img/word_thn.png'/></a>";
                    } elseif ($isMedia == "xlsx") {
                        $media_id = $messagesRows->id;
                        $message = "<a href='php/download_media.php?media_id=$media_id'><img class='mediaDocumentThn' src='img/excel_thn.png'/></a>";
                    } elseif ($isMedia == "pptx") {
                        $media_id = $messagesRows->id;
                        $message = "<a href='php/download_media.php?media_id=$media_id'><img class='mediaDocumentThn' src='img/powerpoint_thn.png'/></a>";
                    } elseif ($isMedia == "pdf") {
                        $media_id = $messagesRows->id;
                        $message = "<a href='php/download_media.php?media_id=$media_id'><img class='mediaDocumentThn' src='img/pdf_thn.png'/></a>";
                    } elseif ($isMedia == "zip") {
                        $media_id = $messagesRows->id;
                        $message = "<a href='php/download_media.php?media_id=$media_id'><img class='mediaDocumentThn' src='img/zip_thn.png'/></a>";
                    } elseif ($isMedia == "exe") {
                        $media_id = $messagesRows->id;
                        $message = "<a class='exeHint' href='php/download_media.php?media_id=$media_id'><img class='mediaDocumentThn' src='img/application_thn.png'/></a>";
                    } else {
                        $message = "<img src='" . $path . $message . "." . $isMedia . "'/>";
                    }
                } else {
                    $message = "<span class='messageText'>$message</span>";
                }
                $sent = date_create($messagesRows->sent);
                $read = $messagesRows->isRead;
                $sentFormatted = date_format($sent, 'H:i');
                if ($speaker_id == $user_id) {
                    echo "<div class='chatRight'>";
                    if ($lastuser != $speaker_id) {
                        echo "<div class='bubble'>";
                    } else {
                        echo "<div class='bubbleManuallyRight'>";
                    }
                    echo $message;
                    echo "<span class='time'>$sentFormatted</span>";
                    if ($read == 1) {
                        echo "<i class='material-icons-small doneAll'>done</i>";
                    } else {
                        echo "<i class='material-icons-small done'>done</i>";
                    }
                    echo "</div>";
                    if ($lastuser != $speaker_id) {
                        $userInfosQuery = mysqli_query($db, "SELECT portrait FROM users WHERE id = $user_id");
                        if (mysqli_num_rows($userInfosQuery)) {
                            $userInfosRow = mysqli_fetch_object($userInfosQuery);
                            // Portrait
                            $mePortrait = $userInfosRow->portrait;
                            if (!file_exists("../../data/portraits/" . $mePortrait) || $mePortrait == "") {
                                $mePortrait = "portraits/default.png";
                            } else {
                                $mePortrait = "portraits/" . $mePortrait;
                            }
                        }
                        echo "<span id='myPortrait'>";
                        echo "<img src='../data/$mePortrait' class='img_round' style='margin-left: 10px;'/>";
                        echo "</span>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class='chatLeft'>";
                    if ($lastuser != $speaker_id) {
                        $MemberInfosQuery = mysqli_query($db, "SELECT portrait FROM users WHERE id = $speaker_id");
                        if (mysqli_num_rows($MemberInfosQuery)) {
                            $MemberInfosRow = mysqli_fetch_object($MemberInfosQuery);
                            // Portrait
                            $memberPortrait = $MemberInfosRow->portrait;
                            if (!file_exists("../../data/portraits/" . $memberPortrait) || $memberPortrait == "") {
                                $memberPortrait = "portraits/default.png";
                            } else {
                                $memberPortrait = "portraits/" . $memberPortrait;
                            }
                        }
                        echo "<span>";
                        echo "<img src='../data/$memberPortrait' class='img_round' style='margin-right: 10px;'/>";
                        echo "</span>";
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
            <div id="attacherHeader">
                <i class="material-icons-large">file_upload</i> <span>Datei hochladen</span>
                <span class="attachHeaderErrors"></span>
            </div>
            <div id="attacherBody">
                <!-- Loader -->
                <div class="loader uploadLoader">
                    <svg class="circular" viewBox="25 25 50 50">
                        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"
                                stroke-miterlimit="10"/>
                    </svg>
                </div>

                <div id="uploadContainer">
                    <span id="uploadContainerText">
                    <div id="uploadFile" class="uploadButton">
                        <input type="file" class="uploader"/>
                        <i class="material-icons">add</i>
                    </div>
                    Klicken oder per Drag-and-Drop fallen lassen</span>
                    <div id="uploadContainerTextForDrop">Hier fallen lassen</div>
                </div>
            </div>
        </div>
        <div class="chatTextBox" contenteditable="true" placeholder="Tippe eine Nachricht"></div>
        <i id="smiley" class="material-icons hover tooltip" title="Smileys">mood</i>
        <i id="attach" class="material-icons hover tooltip" title="Datei teilen">attach_file</i>
        <i id="send" class="material-icons hover tooltip" title="Senden" style="margin-left: 20px;">send</i>
    </div>

    <?php
    /*
    ?>
    } else {
        echo "<div id='noChatActive'><i class='material-icons'>chat_bubble_outline</i> Kein Freund ausgewählt</div>";
    }
    */
    ?>
</div>
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