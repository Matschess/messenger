<!DOCTYPE html>
<HTML lang="de" spellcheck="false">
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
	<script src="js/forgot_js.js"></script>
	<script type="text/javascript" src="plugins/tooltipster/js/jquery.tooltipster.min.js"></script>
  </HEAD>
  <BODY>
  <div id="overlay"></div>

 	 <div id="loginbox" class="loginboxPre">
		<div id="loginboxHeader">
			<a href="login.php">
				<i id="iconEmail" class="material-icons-navigation">keyboard_arrow_left</i></a>
			<span class="verticalCenter">Konto-Wiederherstellung</span>
		</div>
		<div id="loginboxContent">
			<div id="loginboxContentMargin">
				<p>Bitte gib deine Email-Adresse ein.</p>
				<br/>
				<input id="email" class="loginTextbox" type="text">
					<i id="iconEmail" class="material-icons-animated margin-left">email</i>
				</input>
				<br/>
				<br/>
				<br/>
				<input id="okNow" class="registerButton" type="button" value="OK"></input>
			</div>
		</div>
	</div>

	<div id="popup">
		<div id="popupHeader">
			<span id="popupTitle">Konto gesperrt</span>
		</div>
		<div id="popupContent">
			<div id="popupText">
				Entschuldigung, dein Konto ist gesperrt.
				<br/>
				<br/>
				Wenn dein Konto gesperrt ist, kannst du dein Passwort
				nicht zurücksetzten.
			</div>
		</div>
		<div id="popupFooter">
			<input type="button" class="popupButton closePopup" value="Ok"/>
		</div>
	</div>
  </BODY>
</HTML>