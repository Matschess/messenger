<?php

$file_dir = '../../data/media/'; // Directory to the medias of the chats

$media_ids = $_GET["media_id"];
$chat_id = $_COOKIE["chat_id"];
$file_dir .= $chat_id . "/";

include("db_connect.php");
if (count($media_ids) == 1) {
    $media_id = $media_ids[0];
    $mediaExistsQuery = mysqli_query($db, "SELECT dataname, datatype FROM media WHERE id = $media_id && chats_id = $chat_id");
    if (mysqli_num_rows($mediaExistsQuery)) {
        $mediaRows = mysqli_fetch_object($mediaExistsQuery);
        $dataname = $mediaRows->dataname;
        $datatype = $mediaRows->datatype;
        $file = $file_dir . $dataname . "." . $datatype;

        $downloadname = createRandom();

        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$downloadname.$datatype");
        header("Content-Length: " . filesize($file));
        readfile($file);
    } else {
        echo "error";
    }
} else {
    $zipPath = "../../data/download/";
    if (!file_exists($zipPath)) {
        mkdir($zipPath, 0777, true);
    }
    $files = $media_ids;
    $zip = new ZipArchive();
    $zipName = createRandom() . ".zip";
    $zipPath .= $zipName;
    $res = $zip->open("$zipPath", ZipArchive::CREATE);
    if ($res) {
        for ($i = 0; $i < count($files); $i++) {
            $mediaExistsQuery = mysqli_query($db, "SELECT dataname, datatype FROM media WHERE id = $files[$i] && chats_id = $chat_id");
            if (mysqli_num_rows($mediaExistsQuery)) {
                $mediaRows = mysqli_fetch_object($mediaExistsQuery);
                $dataname = $mediaRows->dataname;
                $datatype = $mediaRows->datatype;

                $file = $file_dir . $dataname . "." . $datatype;
                $zip->addFile($file, $dataname . "." . $datatype);
            }
        }
        $zip->close();
        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=$zipName");
        header("Content-Length: " . filesize($zipPath));
        readfile($zipPath);
    } else {
        echo "Fehler beim Erstellen der ZIP-Datei.";
    }
}

function createRandom()
{
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $shuffled = str_shuffle($str);
    $shuffled = substr($shuffled, 0, 15);
    return $shuffled;
}