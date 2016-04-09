<!DOCTYPE html>
<HTML lang="de" spellcheck="false">
  <HEAD>
	<title>Messenger - Anmelden</title>
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
  <?php
	$email = $_GET["email"];
	
	echo "<input type='hidden' id='emailConfirmed' value='$email'/>";	
  ?>
	<div id="overlay"></div>

  <div id="loginbox" class="loginboxPre">
	  <div id="loginboxHeader">
		  <a href="login.php">
			  <i id="iconEmail" class="material-icons-navigation">keyboard_arrow_left</i></a>
		  <span class="verticalCenter">Konto-Wiederherstellung</span>
	  </div>
		<div id="loginboxContent">
			<div id="loginboxContentMargin">
				<p>Bitte gib den PIN-Code ein.</p>
				<p>Du hast ihn gerade per Email erhalten.</p>
				<br/>
				<input id="pin" class="loginTextbox" type="text">
					<i id="iconPin" class="material-icons-animated margin-left">fiber_pin</i>
				</input>
				<br/>
				<br/>
				<br/>
				<input id="authorizeNow" class="registerButton" type="button" value="OK"></input>
			</div>
		</div>
	</div>
	
	<div id="popup">
		<div id="popupHeader">
			<span id="popupTitle">Passwort zurücksetzen</span>
			<img src="img/close_icon.png" id="popupClose" class="icon_medium"></img>
		</div>
		<div id="popupContent">
			<div id="popupText">
			<span class="infoText">Gib hier dein neues Passwort ein.
				<br/>
				Hoffentlich merkst du es dir diesmal besser.
			</span>
			<br/>
			<br/>
			<i id="iconPassword" class="material-icons">lock_outline</i>
			<input id="password" class="textbox_flat" type="password"></input>
			<br/>
			<br/>
			<i id="iconPasswordCheck" class="material-icons">lock_outline</i>
			<input id="passwordCheck" class="textbox_flat" type="password"></input>
			<img src="img/true_icon_black.png" id="passwordTrue" class="icon_check">
			<img src="img/delete_icon_black.png" id="passwordFalse" class="icon_check tooltip" title="Die Passwörter stimmen nicht überein, sind ungültig oder zu kurz.">
			</div>
		</div>
		<div id="popupFooter">
			<input type="button" id="goBack" class="popupButton" value="Ok"/>
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