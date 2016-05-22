<HTML>
<HEAD>
	<link rel="stylesheet" href="css/createGroup_style.css">
	<script src="plugins/Vague.js/Vague.js"></script>
	<script src="js/createGroup_js.js"></script>
</HEAD>
<BODY>
	<div id="popupText">
        <div id="groupPortrait">
			<div class="loader groupPortraitLoader">
				<svg class="circular" viewBox="25 25 50 50">
					<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10"/>
				</svg>
			</div>

            <input type="file" id="groupPortraitUploader" title="Gruppenbild hochladen"/>

            <img src="../data/portraits/default.png" id="groupPortraitImg">
        </div>
		<span class="infoText divRight">Du kannst auch ein Bild hochladen.
		Alternativ wird das Standardbild angezeigt.</span>
	</div>
	<div id="popupFooter">
		<input type="button" class="popupButton groupCancel" value="Abbrechen"/>
		<input type="button" class="popupButton groupPortraitDone" value="Weiter"/>
	</div>
</BODY>
</HTML>