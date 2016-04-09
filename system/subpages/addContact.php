<HTML>
<HEAD>
	<link rel="stylesheet" href="css/addContact_style.css">
	<script src="js/addContact_js.js"></script>
</HEAD>
<BODY>
		<div id="popupText">
			<span class="infoText">Über die Kontaktsuche kannst du neue Kontakte hinzufügen. Gib den Kontaktnamen oder die ID ein:</span>
			<br/>
			<br/>
			<input type="text" id="contactname" class="textbox_flat short color_white"></input>
			<i id="contactLoupe" class="material-icons hover">search</i>
			<div id="foundContact"></div>
		</div>
	

		
		<div id="popupFooter">
			<input type="button" class="popupButton popupClose" value="Abbrechen"/>
			<input type="button" class="popupButton searchContactname" value="Ok"/>
		</div>
</BODY>
</HTML>