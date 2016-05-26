<!DOCTYPE html>
<HTML lang="de">
<HEAD>
    <link rel="stylesheet" href="css/special/checkbox_style.css">
    <link rel="stylesheet" href="css/special/flippswitch_style.css">
    <script src="plugins/Vague.js/Vague.js"></script>
    <script src="js/profile_js.js"></script>
</HEAD>
<BODY>
<div id="Content">
    <?php
    $toRoot = "../";
    include($toRoot . "variables/user_id.php");

    include("db_connect.php");

    $profileDataQuery = mysqli_query($db, "SELECT id, username, firstname, lastname, portrait, statustext, color, isPublic FROM users WHERE id = '$user_id'");
    $profileDataRows = mysqli_fetch_object($profileDataQuery);

    // show full name or username
    $firstname = $profileDataRows->firstname;
    $lastname = $profileDataRows->lastname;
    $username = $profileDataRows->username;
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
    } else {
        $name = "?";
    }

    // Portrait
    $portrait = $profileDataRows->portrait;
    $fullPortrait = "../data/portraits/" . $portrait;
    if (!file_exists("../" . $fullPortrait) || $portrait == "") {
        $fullPortrait = "../data/portraits/default.png";
    }

    $statustext = $profileDataRows->statustext;
    $color = $profileDataRows->color;
    $isPublic = $profileDataRows->isPublic;

    echo "<div id='portrait'>";
    echo "<div class='loader portraitLoader'>";
    echo "<svg class='circular' viewBox='25 25 50 50'>";
    echo "<circle class='path' cx='50' cy='50' r='20' fill='none' stroke-width='3' stroke-miterlimit='10'/>";
    echo "</svg>";
    echo "</div>";
    echo "<img src='$fullPortrait' id='portraitImage'/>";
    echo "<div id='portraitOptions'>";
    echo "<div id='portraitChange'>";
    echo "<i class='material-icons'>photo_camera</i>";
    echo "<input type='file' id='portraitUploadInput' class='tooltip' title='Profilbild ändern'/>";
    echo "</div>";
    echo "<i id='portraitDelete' class='material-icons tooltip' title='Profilbild löschen'>delete</i>";
    echo "</div>";
    echo "</div>";
    echo "<br/>";
    echo "<span id='profileText'>$name</span>";
    echo "<br/>";
    if ($username) {
        echo "<span id='userID' class='tooltip' title='Mit dieser ID kannst du von deinen Freunden <br/> über die Kontaktsuche gefunden werden.'>$username</span>";
    }
    echo "<br/>";
    echo "<br/>";
    echo "<i class='material-icons color_grey'>format_quote</i>";
    if ($statustext == "") {
        echo "<span id='myStatus' class='statusNone'>Klicke um einen Status hinzuzufügen</span>";
        echo "<input type='text' id='myStatusEdit'>";
    } else {
        echo "<span id='myStatus' class='status'>$statustext</span>";
        echo "<input type='text' id='myStatusEdit' value='$statustext'/>";
    }
    echo "<i class='material-icons-small hover' id='myStatusRenewButton'>done</i>";
    echo "<i class='material-icons-small hover' id='myStatusCancelButton'>clear</i>";
    ?>

    <div id="settings">
        Profilfarbe
        <div class="onoffswitch">
            <?php
            if ($color) {
                echo "<input type='checkbox' id='automaticColors' class='onoffswitch-checkbox'>";
            } else {
                echo "<input type='checkbox' id='automaticColors' class='onoffswitch-checkbox' checked>";

            }
            ?>
            <label class="onoffswitch-label" for="automaticColors"></label>
        </div>
        <?php
        if ($color) {
            echo " <i id='myColor' class='material-icons-small hover tooltip' title='Eigene Farbe wählen' style='color: $color'>color_lens</i>";
        }
        ?>
        <br/>
        <br/>

        <div class="selectBox">
            <div class="selectBoxHeader">
                <i class="material-icons-small">visibility</i>
                Profilsichtbarkeit
                <i class="material-icons-small">keyboard_arrow_down</i>
            </div>
            <?php
            if ($isPublic) {
                echo "<div id='public' class='selectBoxOption selectBoxSelected'>";
            } else {
                echo "<div id='public' class='selectBoxOption'>";
            }
            ?>
            <i class="material-icons-small">public</i>
            Öffentlich
        </div>
        <?php
        if (!$isPublic) {
            echo "<div id='friends' class='selectBoxOption selectBoxSelected'>";
        } else {
            echo "<div id='friends' class='selectBoxOption'>";
        }

        ?>
        <i class="material-icons-small">people</i>
        nur Freunde

    </div>
</div>
</div>
</BODY>
</HTML>

<script>
    $(document).ready(function () {

    });
</script>