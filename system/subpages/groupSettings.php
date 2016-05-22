<!DOCTYPE html>
<HTML lang="de">
<HEAD>
    <link rel="stylesheet" href="css/profile_style.css">
    <link rel="stylesheet" href="css/groupSettings_style.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="js/groupSettings_js.js"></script>
</HEAD>
<BODY>
<?php
$toRoot = "../";
include($toRoot . "variables/user_id.php");

include("db_connect.php");

if (isset($_COOKIE["chat_id"])) {
    $chat_id = $_COOKIE["chat_id"];
    echo "<div id='Content'>";
    $groupInfo = mysqli_query($db, "SELECT groupname, portrait FROM chats WHERE id = $chat_id");
    if (mysqli_num_rows($groupInfo)) {
        $groupInfoRow = mysqli_fetch_object($groupInfo);
        $groupname = $groupInfoRow->groupname;

        // Portrait
        $portrait = $groupInfoRow->portrait;

        if (!file_exists("../../data/groupportraits/" . $portrait) || $portrait == "") {
            $portrait = "portraits/default.png";
        } else {
            $portrait = "groupportraits/" . $portrait;
        }

        echo "<div id='portrait'>";
        echo "<div class='loader portraitLoader'>";
        echo "<svg class='circular' viewBox='25 25 50 50'>";
        echo "<circle class='path' cx='50' cy='50' r='20' fill='none' stroke-width='3' stroke-miterlimit='10'/>";
        echo "</svg>";
        echo "</div>";
        echo "<img src='../data/$portrait' id='portraitImage'/>";
        echo "<div id='portraitOptions'>";
        echo "<div id='portraitChange'>";
        echo "<i class='material-icons'>photo_camera</i>";
        echo "<input type='file' id='portraitUploadInput' class='tooltip' title='Profilbild ändern'/>";
        echo "</div>";
        echo "<i id='portraitDelete' class='material-icons tooltip' title='Profilbild löschen'>delete</i>";
        echo "</div>";
        echo "</div>";
        
        echo "<div id='portraitBackground'>";
        echo "<br/>";
        echo "<span id='groupName'>$groupname</span>";
        echo "<input type='text' id='groupNameEdit' value='$groupname'/>";
        echo "<i class='material-icons-small hover' id='groupNameRenewButton'>done</i>";
        echo "<i class='material-icons-small hover' id='groupNameCancelButton'>clear</i>";
        echo "<br/>";

        echo "</div>";

        $groupMembers = mysqli_query($db, "SELECT users.id, users.firstname, users.lastname, users.portrait FROM groupmembers LEFT JOIN users ON users.id = groupmembers.user_id WHERE groupmembers.chat_id = $chat_id && groupmembers.admin ORDER BY users.firstname, users.lastname");
        echo "<div id='$friend_id' class='button buttonEnd leaveGroup'><i class='material-icons-small'>close</i> Gruppe verlassen</div>";
        $groupAdministratorsNumber = mysqli_num_rows($groupMembers);
        if ($groupAdministratorsNumber) {
            echo "<br/>";
            echo "<div id='members'>";
            echo "<div id='groupMembers'>";
            if ($groupAdministratorsNumber == 1) {
                echo "<span class='infoText''>Gruppenadministrator:</span>";
            } else {
                echo "<span class='infoText''>Gruppenadministratoren:</span>";
            }
            echo "<br/>";
            echo "<div id='groupAdministrators'>";
            while ($groupMembersRows = mysqli_fetch_object($groupMembers)) {
                $member_id = $groupMembersRows->id;
                $firstname = $groupMembersRows->firstname;
                $lastname = $groupMembersRows->lastname;
                $portrait = $groupMembersRows->portrait;

                $fullPortrait = "../data/portraits/" . $portrait;
                if (!file_exists("../" . $fullPortrait) || $portrait == "") {
                    $fullPortrait = "../data/portraits/default.png";
                }

                if ($member_id == $user_id) {
                    echo "<div id='member" . $member_id . "' class='chip chipMeAdministrator'><img src='" . $fullPortrait . "'/> <span>Ich</span></div>";
                } else {
                    echo "<div id='member" . $member_id . "' class='chip chipAdministrator'><img src='" . $fullPortrait . "'/> <span>" . $firstname . " " . $lastname . " <i class='material-icons-thin groupMemberDelete'>close</i></span></div>";
                }
            }
            echo "</div>";


            echo "<div class='chipEmptyAdministrators'></div>";
            echo "<br/>";
            echo "<br/>";
            //echo "</div>";
            //echo "<input type='text' id='groupMemberSearch' autofocus/>";
            $groupMembers = mysqli_query($db, "SELECT users.id, users.firstname, users.lastname, users.portrait FROM groupmembers LEFT JOIN users ON users.id = groupmembers.user_id WHERE groupmembers.chat_id = $chat_id && groupmembers.admin = false ORDER BY users.firstname, users.lastname");
            $groupMembersNumber = mysqli_num_rows($groupMembers);
            if ($groupMembersNumber) {
                if ($groupMembersNumber == 1) {
                    echo "<span class='infoText''>Gruppenmitglied:</span>";
                } else {
                    echo "<span class='infoText''>Gruppenmitglieder:</span>";
                }
                echo "<br/>";
                while ($groupMembersRows = mysqli_fetch_object($groupMembers)) {
                    $member_id = $groupMembersRows->id;
                    $firstname = $groupMembersRows->firstname;
                    $lastname = $groupMembersRows->lastname;

                    $portrait = $groupMembersRows->portrait;
                    $fullPortrait = "../data/portraits/" . $portrait;
                    if (!file_exists("../" . $fullPortrait) || $portrait == "") {
                        $fullPortrait = "../data/portraits/default.png";
                    }
                    if ($member_id == $user_id) {
                        echo "<div id='member" . $member_id . "' class='chip chipMember chipMeMember'><img src='" . $fullPortrait . "'/> <span>Ich</span></div>";
                    } else {
                        echo "<div id='member" . $member_id . "' class='chip chipMember'><img src='" . $fullPortrait . "'/> <span>" . $firstname . " " . $lastname . " <i class='material-icons-thin groupMemberDelete'>close</i></span></div>";
                    }
                }
            }
            echo "<div class='chipEmptyMembers''></div>";
            $defaultPortrait = "../data/portraits/default.png";
            echo "<div class='memberAdd'>";
            echo "<div class='chip chipMemberAdd'><img src='" . $defaultPortrait . "'/> <span>Mitglied hinzufügen</span></div>";
            echo "<input type='text' id='groupMemberSearch' placeholder='Tippe zum Suchen' autofocus/>";
            echo "<div id='friendSuggestions'></div>";
            echo "</div>";
            echo "</div>";
        }

    }
} else {
    echo "error";
}
?>
</BODY>
</HTML>