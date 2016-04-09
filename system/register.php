<!DOCTYPE html>
<HTML lang="de">
  <HEAD>
	<title>Messenger - Anmelden</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="stylesheet" href="css/register_style.css">
	<link rel="stylesheet" href="css/popup.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="plugins/tooltipster/css/tooltipster.css"/>
	<link rel="stylesheet" type="text/css" href="plugins/tooltipster/css/themes/tooltipster-custom.css"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="js/register_js.js"></script>
	<script type="text/javascript" src="plugins/tooltipster/js/jquery.tooltipster.min.js"></script>
  </HEAD>
  <BODY>
	<div id="overlay"></div>

	<div id="loginbox" class="loginboxPre">
		<div id="loginboxHeader">
		<a href="login.php">
			<i id="iconEmail" class="material-icons-navigation">keyboard_arrow_left</i></a>
			<span class="verticalCenter">Konto erstellen</span>
		</div>
		<div id="loginboxContent">
			<div id="loginboxContentMargin">
				<p>Zum Registrieren bitte alle Felder ausfüllen.</p>
				<br/>
				<input id="email" class="loginTextbox" type="text" title="Email">
					<i id="iconEmail" class="material-icons-animated margin-left">email</i>
				</input>
				<i id="emailTrue" class="material-icons icon_check">done</i>
				<i id="emailFalse" class="material-icons icon_check tooltip" title="Die Email-Adresse wurde bereits verwendet oder entspricht nicht dem vorgegebenen Format.">clear</i>
				<br/>
				<input id="firstname" class="loginTextboxShort" type="text" placeholder="Vorname" title="Vorname"/>
				<input id="lastname" class="loginTextboxShort" type="text" placeholder="Nachname" title="Nachname"/>
				<i id="nameTrue" class="material-icons">done</i>
				<br/>
				<input id="username" class="loginTextboxLong" type="text" placeholder="Benutzername (mind. 6 Zeichen)" title="Benutzername"/>
				<i id="usernameTrue" class="material-icons">done</i>
				<i id="usernameFalse" class="material-icons tooltip" title="Der Benutzername ist vergeben, ungültig oder kürzer als 6 Zeichen.">clear</i>
				<br/>
				<input id="password" class="loginTextbox" type="password" title="Passwort">
					<i id="iconPassword" class="material-icons-animated margin-left">lock_outline</i>
				</input>
				<i id="passwordTrue" class="material-icons icon_check">done</i>
				<i id="passwordFalse" class="material-icons icon_check tooltip" title="Das Passwort ist zu unsicher">clear</i>
				<br/>
				<div id="passwordSafety"><div id="passwordSafetyStatus"></div></div>
				<input id="passwordCheck" class="loginTextbox" type="password" title="Passwort wiederholen">
					<i id="iconPasswordCheck" class="material-icons-animated margin-left">lock_outline</i>
				</input>
				<i id="passwordCheckTrue" class="material-icons icon_check">done</i>
				<i id="passwordCheckFalse" class="material-icons icon_check tooltip" title="Die Passwörter stimmen nicht überein">clear</i>
				<br/>
				<br/>
				<br/>
				<input id="registerNow" type="button" class="registerButton" value="Registrieren"/>
			</div>
		</div>
	</div>
	<div id="popup">
		<div id="popupHeader">
			Konto erstellt
		</div>
		<div id="popupContent">
			<div id="popupText">
			<span class="infoText">
				Dein neues Konto wurde erfolgreich eingerichtet.
				<br/>
				<br/>
				Sieh in deinem Email-Postfach nach, du musst deinen Account
				noch freischalten.
			</span>
			</div>
		</div>
		<div id="popupFooter">
			<input type="button" id="toLogin" class="popupButton" value="Login"/>
		</div>
	</div>
  </BODY>
</HTML>

<script>
        $(document).ready(function() {
            $('.tooltip').tooltipster({
				contentAsHTML: true,
				animation: 'grow',
				delay: 250,
				theme: 'tooltipster-custom',
				trigger: 'hover'
}			);
        });
</script>