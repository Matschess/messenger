<!DOCTYPE html>
<HTML lang="de">
<HEAD>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/index_style.css">
    <link rel="stylesheet" href="css/popup.css">
    <link rel="stylesheet" href="css/chat_style.css">
    <link rel="stylesheet" href="css/profile_style.css">
    <link rel="stylesheet" href="css/special/ripple_effect_style.css">
    <link rel="stylesheet" href="css/special/loading_style.css">
    <link rel="stylesheet" type="text/css" href="plugins/tooltipster/css/tooltipster.css"/>
    <link rel="stylesheet" type="text/css" href="plugins/tooltipster/css/themes/tooltipster-custom.css"/>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="js/index_js.js"></script>
    <script type="text/javascript" src="plugins/tooltipster/js/jquery.tooltipster.min.js"></script>
    <script src="plugins/color-thief-master/src/color-thief.js"></script>
    <script src="plugins/jqueryCookies/jquery.cookie.js"></script>
    <title>Messenger</title>
    <?php
    session_start();

    include("php/db_connect.php");

    if (empty($_SESSION["user_id"])) {
        header("location: login.php");
    } else {
        $user_id = $_SESSION["user_id"];
        $userQuery = mysqli_query($db, "SELECT portrait, color FROM users WHERE id = $user_id");
        $userRow = mysqli_fetch_object($userQuery);
        $user_portrait = $userRow->color;
        $_SESSION["user_portrait"] = $user_portrait;
        $user_color = $userRow->color;
        setcookie("messengerColor", $user_color, 0, '/'); // Set cookie until browser is closed;
        echo "<meta name='theme-color' content='$user_color'/>";
    }
    ?>
</HEAD>
<BODY>
<div id="overlay"></div>

<div class="bodyBefore">
    <div id="container">
        <div id="containerLeft" class="col-4">
            <table class="tableNavigation">
                <tr>
                    <td id="toChats" class="ripple navigationHover">Chats</td>
                    <td id="toContacts" class="ripple navigationHover">Kontakte</td>
                </tr>
            </table>
            <div id="containerLeftSearch">
                <input type="text" class="textbox color_green contactSearchbox"></input>
                <i class="material-icons contactSearchIcon">search</i>
            </div>
            <div id="contacts"></div>
        </div>
        <div id="containerRight" class="col-8">
            <table class="tableNavigation">
                <tr>
                    <td id="toChat" class="ripple navigationHover">Chat</td>
                    <td id="toMedia" class="ripple navigationHover">Medienbox</td>
                    <td id="toProfile" class="ripple navigationHover">Profil</td>
                    <td class="navigationNonHover">
                        <div id="me">
                            <?php
                            $result = mysqli_query($db, "SELECT * FROM users WHERE id = $user_id");
                            $row = mysqli_fetch_object($result);
                            $portrait = $row->portrait;
                            $fullPortrait = "../data/portraits/" . $portrait;
                            if (!file_exists($fullPortrait) || $portrait == "") {
                                $fullPortrait = "../data/portraits/default.png";
                            }
                            echo "<img src='$fullPortrait' id='mePortrait' class='img_round_flat ripple'></img>";
                            $result = mysqli_query($db, "SELECT COUNT(id) AS enquiries FROM contacts WHERE user_id = $user_id && NOT accepted");
                            $row = mysqli_fetch_object($result);
                            $enquiries = $row->enquiries;
                            if ($enquiries) {
                                echo "<div id='portraitAlert'>!</div>";
                            }
                            else {
                                echo "<div id='portraitAlert' style='display: none'>!</div>";
                            }
                            ?>
                        </div>
                        <div id="logout" class="meOption">
                            <i class="material-icons tooltip" title="Ausloggen und beenden">vpn_key</i>
                        </div>

                        <div id="profileSettings" class="meOption">
                            <i class="material-icons tooltip" title="Profil-Einstellungen">settings</i>
                        </div>

                        <div id="notifications" class="meOption">
                            <i class="material-icons tooltip" title="Benachrichtigungen ein/aus">volume_off</i>
                        </div>
                        <div id="enquiry" class="meOption">
                            <?php
                            if ($enquiries) {
                                echo "<div id='enquiries'>$row->enquiries</div>";
                            }
                            else {
                                echo "<div id='enquiries' style='display: none'></div>";
                            }
                            ?>
                            <i class="material-icons tooltip" title="Freundschaftsanfragen">people</i>
                        </div>
                        <div id="add" class="meOption">
                            <i class="material-icons tooltip" title="Freund hinzufügen">add</i>
                        </div>


                    </td>
                </tr>
            </table>
            <div class="content">
            </div>
        </div>
    </div>
</div>

<div id="popup">
    <div id="popupHeader"><span id="popupTitle">Kontaktsuche</span></div>
    <div id="popupContent"></div>
</div>

<div class="loader contentLoaderPattern">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"
                stroke-miterlimit="10"/>
    </svg>
</div>
</BODY>
</HTML>