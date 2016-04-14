$(document).ready(function () {
    $('.groupMemberDelete').click(function () {
        window.memberToDelete = $(this).parent().parent().attr('id').substr(6);

        $('#popupHeader').html("Mitglied entfernen?");
        $('#popupContent').load('subpages/deleteGroupMemberReally.php', function () {
            $('#overlay').fadeIn(200);
            $('#popup').fadeIn(200);
        });
    });

    $('#popupContent').on("click", ".deleteGroupMemberNow", function () {
        $member_id = window.memberToDelete;

        $.get('php/groupMemberEdit.php?job=delete&member_id=' + $member_id, function (data) {
            if(data == 'deleted') {
                $('#overlay').fadeOut(200);
                $('#popup').fadeOut(200);
                reload();
            }
        });
    });

    function reload() {
        $('.content').load('subpages/groupSettings.php');
    }
});