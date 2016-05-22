<HTML>
<HEAD>
    <link rel="stylesheet" href="css/createGroup_style.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="js/createGroup_js.js"></script>
</HEAD>
<BODY>
<div id="popupText">
    <span class="infoText">Gruppenadministratoren:</span>
    <br/>
    <div id="groupMembers">
        <div id="groupAdministrators">
            <div id="member153" class="chipGroupCreator">
                <?php
                include("db_connect.php");

                $portraitQuery = mysqli_query($db, "SELECT portrait FROM users WHERE id = '$user_id'");
                $portraitRows = mysqli_fetch_object($portraitQuery);
                $portrait = $portraitRows->portrait;
                $fullPortrait = "../data/portraits/" . $portrait;
                if (!file_exists("../" . $fullPortrait) || $portrait == "") {
                    $fullPortrait = "../data/portraits/default.png";
                }

                echo "<img src = '$fullPortrait'/>";
                ?>
                <span>Ich</span>
            </div>
        </div>
        <div class="chipEmptyAdministrators"></div>
        <br/>
        <br/>
        <span class="infoText">Gruppenmitglieder:</span>
        <br/>
    </div>
    <div class="chipEmptyMembers"></div>
    <input type="text" id="groupMemberSearch" placeholder='Suchen' autofocus></input>
    <div id="friendSuggestions"></div>
</div>
<div id="popupFooter">
    <input type="button" class="popupButton groupCancel" value="Abbrechen"/>
    <input type="button" class="popupButton createGroupNow" value="Fertigstellen"/>
</div>
</BODY>
</HTML>