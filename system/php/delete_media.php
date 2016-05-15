<?php

$file_dir = '../../data/media/'; // Directory to the medias of the chats

if (isset($_GET["media_id"])) {
    $media_ids = $_GET["media_id"];
} else {
    $everything = $_GET["everything"];
}

$chat_id = $_COOKIE["chat_id"];
$file_dir .= $chat_id . "/";

include("db_connect.php");
if ($everything) {
    $mediaExistsQuery = mysqli_query($db, "SELECT id, dataname, datatype FROM media WHERE chat_id = $chat_id");
    if (mysqli_num_rows($mediaExistsQuery)) {
        while ($mediaRows = mysqli_fetch_object($mediaExistsQuery)) {
            $media_id = $mediaRows->id;
            $dataname = $mediaRows->dataname;
            $datatype = $mediaRows->datatype;

            if ($mediaDelete = mysqli_query($db, "DELETE FROM media WHERE id = $media_id")) {
                if (unlink($file_dir . $dataname . "." . $datatype)) {

                } else {
                    echo "error";
                    return;
                }
            }
        }
    } else {
        echo "error";
        return;
    }
    echo "deleted";
} elseif ($media_ids) {
    for ($i = 0; $i < count($media_ids); $i++) {
        $media_id = $media_ids[$i];
        $mediaExistsQuery = mysqli_query($db, "SELECT dataname, datatype FROM media WHERE id = $media_id && chat_id = $chat_id");
        if (mysqli_num_rows($mediaExistsQuery)) {
            $mediaRows = mysqli_fetch_object($mediaExistsQuery);
            $dataname = $mediaRows->dataname;
            $datatype = $mediaRows->datatype;

            if ($mediaDelete = mysqli_query($db, "DELETE FROM media WHERE id = $media_id")) {
                if (unlink($file_dir . $dataname . "." . $datatype)) {

                } else {
                    echo "error";
                    return;
                }
            }
        } else {
            echo "error";
            return;
        }
    }
    echo "deleted";
} else {
    echo "error";
}