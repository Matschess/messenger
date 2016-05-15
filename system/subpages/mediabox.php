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
    <?php
    if (isset($_COOKIE["chat_id"]) || isset($_COOKIE["friend_id"])) {
        if(isset($_COOKIE["chat_id"])) {
            $chat_id = $_COOKIE["chat_id"];
        }
        ?>
        <div id="mediaboxInfo">
            <?php
            $toRoot = "../";
            include($toRoot . "variables/user_id.php");

            include("db_connect.php");


            $result = mysqli_query($db, "SELECT * FROM media WHERE chat_id = $chat_id");
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
                <div id="uploadNewMedia" class="option">
                    Datei hochladen
                </div>
                <?php
                if ($countMedias > 0) {
                    echo "<div id='downloadAllMedia' class='option'>";
                    echo "Alles herunterladen";
                    echo "</div>";
                    echo "<div id='deleteAllMedia' class='option'>";
                    echo "Medienbox leeren";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
        <div id="content">
            <?php
            if (mysqli_num_rows($result)) {
                while ($row = mysqli_fetch_object($result)) {
                    $media_id = $row->id;
                    $chat_id = $row->chat_id;
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
                    } elseif ($datatype == 'pdf') {
                        echo "<div id='$media_id' class='thumbnail'><i class='material-icons'>done</i>";
                        echo "<img id='img$media_id' src='img/pdf_thn.png'/>";
                        echo "</div>";
                    } elseif ($datatype == 'mp3') {
                        echo "<div id='$media_id' class='thumbnail'><i class='material-icons'>done</i>";
                        echo "<img id='img$media_id' src='img/music_thn.png'/>";
                        echo "</div>";
                    } elseif ($datatype == 'mp4') {
                        echo "<div id='$media_id' class='thumbnail'><i class='material-icons'>done</i>";
                        echo "<img id='img$media_id' src='img/video_thn.png'/>";
                        echo "</div>";
                    } elseif ($datatype == 'zip') {
                        echo "<div id='$media_id' class='thumbnail'><i class='material-icons'>done</i>";
                        echo "<img id='img$media_id' src='img/zip_thn.png'/>";
                        echo "</div>";
                    } elseif ($datatype == 'exe') {
                        echo "<div id='$media_id' class='exeHint thumbnail'><i class='material-icons'>done</i>";
                        echo "<img id='img$media_id' src='img/application_thn.png'/>";
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
        <?php
    } else {
        echo "<div id='noChatActive'><i class='material-icons'>chat_bubble_outline</i> Kein Freund ausgewählt</div>";
    }
    ?>
    <div id="mediaboxOptions">
        <i id="shareMedia" class="material-icons hover tooltip" title="Weitergeben">share</i>&nbsp;&nbsp;&nbsp;
        <i id="downloadMedia" class="material-icons hover tooltip" title="Herunterladen">file_download</i>&nbsp;&nbsp;&nbsp;
        <i id="deleteMedia" class="material-icons hover tooltip" title="Löschen">delete</i>
    </div>
</div>
</BODY>
</HTML>