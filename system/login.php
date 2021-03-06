<!DOCTYPE html>
<HTML lang="de" spellcheck="false">
<HEAD>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Messenger</title>
    <meta name="theme-color" content="#22303f"/>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/login_style.css">
    <link rel="stylesheet" type="text/css" href="css/login_mobile.css" media="screen and (max-width: 600px)" />
    <link rel="stylesheet" href="css/popup.css">
    <link rel="stylesheet" href="css/special/wiggle_style.css">
    <!-- Extern Stylesheets -->
    <link rel="stylesheet" type="text/css" href="plugins/tooltipster/css/tooltipster.css"/>
    <link rel="stylesheet" type="text/css" href="plugins/tooltipster/css/themes/tooltipster-custom.css"/>
    <link rel="stylesheet" href="css/special/flippswitch_style.css">
    <!-- Fonts -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- JS Files -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <!-- Latest jQuery -->
    <script type="text/javascript" src="js/login_js.js"></script>
    <script type="text/javascript" src="plugins/tooltipster/js/jquery.tooltipster.min.js"></script>
    <script src="plugins/jqueryCookies/jquery.cookie.js"></script>
</HEAD>
<BODY>
<div id="overlay"></div>

<?php
if (isset($_COOKIE['messenger'])) {
    header("location: index.php");
}
?>

<div id="loginbox" class="loginboxPre">
    <div id="loginboxHeader">Messenger</div>
    <div id="loginboxContent">
        <div id="loginboxContentMargin">
            <input id="username" class="loginTextbox" type="text" placeholder="Benutername" title="Benutzername"/>
            <i id="iconUsername" class="material-icons-animated margin-left">face</i>
            <br/>
            <br/>
            <input id="password" class="loginTextbox" type="password" placeholder="Passwort" title="Passwort"/>
            <i id="iconPassword" class="material-icons-animated margin-left">lock_outline</i>
            <br/>
            <br/>
            <span class="flippswitchLabel">Anmeldedaten speichern</span>
            <div class="onoffswitch">
                <input type='checkbox' id='automaticColors' class='onoffswitch-checkbox' checked>
                <label class="onoffswitch-label" for="automaticColors"></label>
            </div>
            <br/>
            <br/>
            <a href="forgot.php" class="loginLink">Passwort vergessen?</a>
            <a href="register.php" class="loginLink">Registrieren</a>
            <br/>
            <br/>
            <br/>
            <input id="loginNow" class="loginButton" type="button" value="Einloggen"/>

        </div>
    </div>
    <span id="versioning" class="versioningPre">Version 0.0.0.1, &copy Matthias Lang</span>
</div>
<div id="popup">
    <div id="popupHeader"><span id="popupTitle">Konto gesperrt</span></div>
    <div id="popupContent">
        <div id="popupText">
            Entschuldigung, dein Konto ist gesperrt.
            <br/>
            <br/>
            Sieh in deinem Email-Postfach nach, in einer Email
            von uns erfährts du wie es weitergeht.
            <i id="emailFalse" class="material-icons-hover tooltip" title="<b>Warum?</b>
				<br/>
				Dein Passwort wurde zu oft falsch eingegeben.
				<br/>
				Aus Sicherheitsgründen, um deine Privatsphäre zu schützen
				<br/>
				haben wir dein Konto gesperrt.">help_outline</i>
        </div>
        <div id="popupFooter">
            <input type="button" class="popupButton closePopup" value="Ok"/>
        </div>
    </div>
</div>
</BODY>
</HTML>