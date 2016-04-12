<HTML>
<HEAD>
    <script src="js/enquiry_js.js"></script>
</HEAD>
<BODY>
<div id="popupText">
    <?php
    if (isset($_GET["number"])) {
        $number = $_GET["number"];
        if ($number == 1) {
            echo "Willst du die Datei wirklich löschen?";
        } else {
            echo "Willst du die Dateien wirklich löschen?<br/>Sie sind nicht wiederherstellbar.";
        }
    } elseif (isset($_GET["everything"])) {
        echo "Willst du die Medienbox wirklich vollständig leeren?";
    }
    else {
        echo "error[noparam]";
    }
    ?>
</div>
<div id="popupFooter">
    <?php
    echo "<input type='button' class='popupButton deleteMediaNow' value='Ja'/>";
    ?>
    <input type="button" class="popupButton popupClose" value="Nein"/>
</div>
</BODY>
</HTML>