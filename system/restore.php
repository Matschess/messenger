<!DOCTYPE html>
<HTML lang="de">
  <HEAD>
  	<link rel="stylesheet" href="css/register_style.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
	<link rel="stylesheet" type="text/css" href="plugins/tooltipster/css/tooltipster.css"/>
	<link rel="stylesheet" type="text/css" href="plugins/tooltipster/css/themes/tooltipster-custom.css"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="js/register_js.js"></script>
	<script type="text/javascript" src="plugins/tooltipster/js/jquery.tooltipster.min.js"></script>
  </HEAD>
  <BODY>
	<span id="loginbox">
		<div id="loginboxHeader">
		<a href="login.php"><img src="img/back_icon.png" class="icon_larger"></img></a>
			<span class="verticalCenter">Konto-Wiederherstellung</span>
		</div>
		<div id="loginboxContent">
			<div id="loginboxContentMargin">
				<p>Bitte gib dein neues Passwort ein.</p>
				<p></p>
				<br/>
				
				<br/>
				<br/>
				<br/>
				<input id="registerNow" class="loginButton" type="button" value="OK"></input>
			</div>
		</div>
	</span>
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