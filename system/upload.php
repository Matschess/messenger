<?php
$toRoot = "/";
include($toRoot . "variables/user_id.php");

include("php/db_connect.php");

$job = $_GET["job"];

if ($job == "portrait") {
    $target_dir = "../data/portraits/"; // Upload-Directory for portraits
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Old filename
    $select = mysqli_query($db, "SELECT portrait FROM users WHERE id = '$user_id'");
    $row = mysqli_fetch_object($select);
    $filenameOld = $row->portrait;

    $fullFilenameOld = $target_dir . $filenameOld;

    getNewName(1);
    validateUpload();
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $fullFilename)) {
            // Delete old Portrait
            $unlink = unlink($fullFilenameOld);
            // Save new Portrait name
            if (mysqli_query($db, "UPDATE users SET portrait = '$filename' WHERE id = '$user_id'")) {
                echo $filename;
            }
        }
    }
} elseif ($job == "groupPortrait") {
    // get chat_id from SESSION
    session_start();
    if (isset($_SESSION["newGroupChatId"])) {
        $chat_id = $_SESSION["newGroupChatId"];

        $target_dir = "../data/groupportraits/"; // Upload-Directory for portraits
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Old filename
        $select = mysqli_query($db, "SELECT portrait FROM chats WHERE id = '$chat_id'");
        $row = mysqli_fetch_object($select);
        $filenameOld = $row->portrait;

        $fullFilenameOld = $target_dir . $filenameOld;

        getNewName(1);
        validateUpload();
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $fullFilename)) {
                // Delete old Portrait
                $unlink = unlink($fullFilenameOld);
                // Save new Portrait name
                if (mysqli_query($db, "UPDATE chats SET portrait = '$filename' WHERE id = '$chat_id'")) {
                    echo $filename;
                }
            }
        }
    }
} elseif ($job == "groupPortraitEdit") {
    $chat_id = $_COOKIE["chat_id"];

    $target_dir = "../data/groupportraits/"; // Upload-Directory for portraits
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Old filename
    $select = mysqli_query($db, "SELECT portrait FROM chats WHERE id = '$chat_id'");
    $row = mysqli_fetch_object($select);
    $filenameOld = $row->portrait;

    $fullFilenameOld = $target_dir . $filenameOld;

    getNewName(1);
    validateUpload();
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $fullFilename)) {
            // Delete old Portrait
            $unlink = unlink($fullFilenameOld);
            // Save new Portrait name
            if (mysqli_query($db, "UPDATE chats SET portrait = '$filename' WHERE id = '$chat_id'")) {
                echo $filename;
            }
        }
    }
} elseif ($job == "media") {
    $chat_id = $_GET["chat_id"];

    if (isset($chat_id)) {
        $target_dir = "../data/media/" . $chat_id . "/"; // Upload-Directory for media
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $filenameOld = "";
        getNewName(0);
        validateUpload();
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $fullFilename)) {
                // Save new Portrait name
                if ($insert = mysqli_query($db, "INSERT INTO media (chat_id, user_id, dataname, datatype) VALUES ($chat_id, $user_id, '$rawFilename', '$fileType')")) {
                    if ($fileType == "docx" || $fileType == "xlsx" || $fileType == "pptx" || $fileType == "pdf" || $fileType == "zip" || $fileType == "exe") { // give back media_id for documents for download
                        $media_id = mysqli_insert_id($db);
                        echo "uploaded: " . $media_id . "." . $fileType;
                    } else {
                        echo "uploaded: " . $fullFilename;
                    }
                }
            }
        }
    } else {
        echo "error[no_chat]";
    }
}

function getNewName($setUsernameBefore)
{
    global $user_id, $chat_id, $target_dir, $rawFilename, $filename, $fullFilename, $filenameOld, $fileType, $uploadOk;

    $str = 'abcdefghijklmnopqrstuvwxyz1234567890';
    $shuffled = str_shuffle($str);
    $shuffled = substr($shuffled, 0, 4);

    $basename = basename($_FILES["file"]["name"]);
    $fileType = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
    if ($setUsernameBefore) {
        $rawFilename = $user_id . $shuffled;
        $filename = $user_id . $shuffled . "." . $fileType;
    } else {
        $rawFilename = $chat_id . $shuffled;
        $filename = $chat_id . $shuffled . "." . $fileType;
    }
    $fullFilename = $target_dir . $filename;
    if ($filename != $filenameOld) {
        $uploadOk = 1;
    } else {
        getNewName();
    }
}

function validateUpload()
{
    global $fileType, $uploadOk;
// Check if image ist fake or not
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }
// Check file size
    if ($_FILES["file"]["size"] > 50000000) {
        $uploadOk = 0;
        echo "error[size]";
    }
// Check file formats
    if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "mp4" && $fileType != "mp3" && $fileType != "docx" && $fileType != "xlsx" && $fileType != "pptx" && $fileType != "pdf" && $fileType != "zip" && $fileType != "exe") {
        $uploadOk = 0;
        echo "error[filetype]";
    }
}