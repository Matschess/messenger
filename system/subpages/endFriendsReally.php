<HTML>
<HEAD>
	<script src="js/enquiry_js.js"></script>
</HEAD>
<BODY>
	<div id="popupText">
		Willst du die Freundschaft wirklich beenden?
		<br/>
		Alle Chats und Medien werden gelöscht.
	</div>
	<div id="popupFooter">
		<?php
			$friend_id = $_GET["friend_id"];
			echo "<input type='button' id='$friend_id' class='popupButton endFriendsNow' value='Ja'/>";
		?>
		<input type="button" class="popupButton popupClose" value="Nein"/>
	</div>
</BODY>
</HTML>