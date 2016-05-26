<HTML>
<HEAD>
    <script src="js/enquiry_js.js"></script>
</HEAD>
<BODY>
<div id="popupText">
    <?php
    include("db_connect.php");

    $toRoot = "../";
    include($toRoot . "variables/user_id.php");

    $result = mysqli_query($db, "SELECT contacts.id, contacts.friend_id, users.username, users.firstname, users.lastname, users.portrait, users.isPublic FROM contacts
	LEFT JOIN users
	ON contacts.friend_id = users.id
	WHERE contacts.user_id = '$user_id' && NOT contacts.accepted;

	");
    if ($result->num_rows) {
        echo "<table class='tableWide'>";
        while ($row = mysqli_fetch_object($result)) {
            $id = $row->id;

            // show full name or username
            $firstname = $row->firstname;
            $lastname = $row->lastname;
            $username = $row->username;
            $name = '';
            if($firstname) {
                $name = $firstname;
                if($lastname) {
                    $name .= " " . $lastname;
                }
            }
            elseif($lastname) {
                $name .= $lastname;

            }
            elseif($username) {
                $name = $username;
            }
            else {
                $name = "?";
            }

            $portrait = $row->portrait;
            $isPublic = $row->isPublic;
            $fullPortrait = "../data/portraits/" . $portrait;
            if (!file_exists("../" . $fullPortrait) || $portrait == "" || !$isPublic) {
                $fullPortrait = "../data/portraits/default.png";
            }
            echo "<tr>";
            echo "<td class='tdShort'><img src='$fullPortrait' class='portraitSmall'></img></td>";
            echo "<td id='$row->friend_id' class='tdLong toProfile'><a href='#' class='link'>" . $name . "</a></td>";
            echo "<td><i id='$id' class='material-icons hover enquiryDiscard'>clear</i></td>";
            echo "<td><i id='$id' class='material-icons hover enquiryAccept'>done</i></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<i class='material-icons-small'>done</i>";
        echo " Alles erledigt!";
    }
    ?>
</div>
<div id="popupFooter">
    <input type="button" class="popupButton popupClose ripple" value="Ok"/>
</div>
</BODY>
</HTML>