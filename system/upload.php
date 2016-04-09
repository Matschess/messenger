<?php
$toRoot = "/";
include($toRoot . "variables/user_id.php");

include("php/db_connect.php");

$target_dir = "../data/portraits/"; // Upload-Directory

// Old filename
$select = mysqli_query($db, "SELECT portrait FROM users WHERE id = '$user_id'");
$row = mysqli_fetch_object($select);
$filenameOld = $row->portrait;

$fullFilenameOld = $target_dir . $filenameOld;

// Create new filename
getNewName();

function getNewName() {
	global $user_id, $target_dir, $filename, $fullFilename, $fileType, $uploadOk;
	
	$str = 'abcdefghijklmnopqrstuvwxyz1234567890';
	$shuffled = str_shuffle($str);
	$shuffled = substr($shuffled, 0, 4);
	
	$basename = basename($_FILES["file"]["name"]);
	$fileType = pathinfo($basename,PATHINFO_EXTENSION);
	$filename = $user_id . $shuffled . "." . $fileType;
	$fullFilename = $target_dir . $filename;
	if($filename != $filenameOld) {
		$uploadOk = 1;
	}
	else {
		getNewName();
	}
}
// Check if image ist fake or not
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }
}
// Check file size
if ($_FILES["file"]["size"] > 50000000) {
    $uploadOk = 0;
}
// Check file formats
if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg") {
    $uploadOk = 0;
}
// Check if uploadOk is 1
if($uploadOk == 1) {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $fullFilename)) {
	// Delete old Portrait
	$unlink = unlink($fullFilenameOld);
	// Save new Portrait name
	echo $filename;
	$insert = mysqli_query($db, "UPDATE users SET portrait = '$filename' WHERE id = '$user_id'");
	}
}
?>