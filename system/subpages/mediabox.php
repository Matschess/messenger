<!DOCTYPE html>
<HTML lang="de">
<HEAD>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/chat_style.css">
    <script src="plugins/Vague.js/Vague.js"></script>
    <script src="js/mediabox_js.js"></script>
</HEAD>
<BODY>
<div id="mediabox">
    <div id="mediaboxInfo">
        <?php
        $toRoot = "../";
        include($toRoot . "variables/user_id.php");

        include("db_connect.php");

        $chat_id = $_COOKIE["chat_id"];

        $result = mysqli_query($db, "SELECT * FROM media WHERE chats_id = $chat_id && users_id = $user_id");
        $countMedias = mysqli_num_rows($result);
        if ($countMedias == 1) {
            echo "1 Datei";
        } elseif ($countMedias > 1) {
            echo $countMedias . " Dateien";
        } else {
            echo "Keine Dateien";
        }
        ?>
        <div id="options">
            <div id="optionsIcon">
                <i class="material-icons hover" style="margin-right: 10px">more_vert</i>
            </div>
            <div id="downloadAllMedia" class="option">
                Alles herunterladen
            </div>
            <div id="deleteMedia" class="option">
                Ordner leeren
            </div>
        </div>
    </div>
    <div id="content">
        <?php
        if (mysqli_num_rows($result)) {
            while ($row = mysqli_fetch_object($result)) {
                $media_id = $row->id;
                $chat_id = $row->chats_id;
                $dataname = $row->dataname;
                $datatype = $row->datatype;
                if ($datatype == 'doc' || $datatype == 'docx') {
                    echo "<div id='$media_id' class='thumbnail'><i class='material-icons'>done</i>";
                    echo "<img id='img$media_id' src='img/word_thn.png'/>";
                    echo "</div>";
                } elseif ($datatype == 'xls' || $datatype == 'xlsx') {
                    echo "<div id='$media_id' class='thumbnail'><i class='material-icons'>done</i>";
                    echo "<img id='img$media_id' src='img/excel_thn.png'/>";
                    echo "</div>";
                } elseif ($datatype == 'ppt' || $datatype == 'pptx') {
                    echo "<div id='$media_id' class='thumbnail'><i class='material-icons'>done</i>";
                    echo "<img id='img$media_id' src='img/powerpoint_thn.png'/>";
                    echo "</div>";
                } else {
                    echo "<div id='$media_id' class='thumbnail'><i class='material-icons'>done</i>";
                    echo "<img id='img$media_id' src='../data/media/$chat_id/$dataname.$datatype'/>";
                    echo "</div>";
                }
            }
        } else {
            echo "<div id='noMedia'><i class='material-icons'>share</i> Teile deine erste Datei</div>";
        }
        ?>
    </div>
    <div id="mediaboxOptions">
        <i id="deleteMedia" class="material-icons hover tooltip" title="Löschen">delete</i>&nbsp;&nbsp;&nbsp;
        <i id="downloadMedia" class="material-icons hover tooltip" title="Herunterladen">save</i>
    </div>
</div>
</BODY>
</HTML>