<HTML>
<HEAD>
	<link rel="stylesheet" href="css/createGroup_style.css">
	<script src="plugins/Vague.js/Vague.js"></script>
	<script src="js/createGroup_js.js"></script>
</HEAD>
<BODY>
	<div id="popupText">
		<span class="infoText">Bitte wähle einen Gruppennamen:</span>
		<br/>
		<br/>
		<div id="groupname" class="textbox_flat short" contenteditable="true"></div>
		<i id="smiley" class="material-icons hover">mood</i>
		<div id="smileyChooserBubble">
			<div id="smileyChooserToPeople" class="tab">
				<i class="material-icons-large">mood</i>
			</div>
			<div id="smileyChooserToNature" class="tab">
				<i class="material-icons-large">nature</i>
			</div>
			<div id="smileyChooserToThings" class="tab">
				<i class="material-icons-large">notifications</i>
			</div>
			<div id="smileyChooserToVehicles" class="tab">
				<i class="material-icons-large">directions_car</i>
			</div>
			<div id="smileyChooserToSigns" class="tab">
				<i class="material-icons-large">message</i>
			</div>

			<div id="smileyChooserContainer"></div>
		</div>
		<span class="charCounter">60</span>
		<br/>
		<br/>
		
	</div>
	<div id="popupFooter">
		<input type="button" class="popupButton groupCancel" value="Abbrechen"/>
		<input type="button" class="popupButton groupNameDone" value="Weiter"/>
	</div>
</BODY>
</HTML>