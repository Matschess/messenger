<?php

$file_dir = '../../data/media/'; // Directory to the medias of the chats

$media_ids = $_GET["media_id"];
$chat_id = $_GET["chat_id"];
$file_dir .= $chat_id . "/";

include("db_connect.php");
if ($media_ids) {
    for ($i = 0; $i < count($media_ids); $i++) {
        $media_id = $media_ids[$i];
        $mediaExistsQuery = mysqli_query($db, "SELECT dataname, datatype FROM media WHERE id = $media_id && chats_id = $chat_id");
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