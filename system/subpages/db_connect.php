<?php
error_reporting(0);
	$db = mysqli_connect("localhost", "root", "root", "messenger");
	if(!$db)
	{
	exit("<br/><i>&nbsp;&nbsp;Verbindung zur Datenbank konnte nicht hergestellt werden. Bitte erneut versuchen!</i>");
	}