$(document).ready(function () {
    $('.chipMemberAdd').click(function () {
        $('.chipMemberAdd').hide();
        $('#groupMemberSearch').show().focus();
    });

    $('.chip').draggable({
        revert: "invalid",
        revertDuration: 80,
        zIndex: 10000,
        drag: function (event, ui) {
            // Adapt width of empty chip
            $('.chipEmptyAdministrators').width(($('#' + this.id).width()));
        }
    });
    /*
    $('.chipEmptyAdministrators').droppable({
        accept: ".chip",
        drop: function (event, ui) {
            $(ui.draggable).css({'top': '0px', 'left': '0px'});
            $(ui.draggable).appendTo('#groupAdministrators');
            $(ui.draggable).addClass('chipAdministrator');
            $(ui.draggable).removeClass('chip');
        }
    });
    $('.chipAdministrator').draggable({
        revert: "invalid",
        revertDuration: 80,
        drag: function (event, ui) {
            // Adapt width of empty chip
            $('.chipEmptyMembers').width(($('#' + this.id).width()));
        }
    });
    $('.chipEmptyMembers').droppable({
        accept: ".chipAdministrator",
        drop: function (event, ui) {
            $(ui.draggable).css({'top': '0px', 'left': '0px'});
            $(ui.draggable).appendTo('#groupMembers');
            $(ui.draggable).addClass('chip');
            $(ui.draggable).removeClass('chipAdministrator');
        }
    });*/

    $(document).click(function (e) {
        if ($(e.target).closest('.memberAdd').length != 0) return false;
        hideAddMemberElements();
    });

    function hideAddMemberElements() {
        $('.chipMemberAdd').show();
        $('#groupMemberSearch').hide();
        $search = $('#groupMemberSearch').val('');
        $('#friendSuggestions').css('display', 'none');
    }

    $('#groupMemberSearch').keyup(function () {
        $search = $('#groupMemberSearch').val();
        if ($search) {
            $.post("php/findUserForGroup.php",
                {
                    job: 'search',
                    search: $search
                },
                function (data) {
                    $('#friendSuggestions').css('display', 'inline-block');
                    $('#friendSuggestions').html('');
                    data = JSON.parse(data);
                    for ($i = 0; $i < data.length; $i++) {
                        $id = data[$i][0];
                        $name = data[$i][1];
                        $portrait = data[$i][2];
                        $('#friendSuggestions').append("<div class='friendSuggestionsUser' id='" + $id + "'>" +
                            "<img src='" + $portrait + "' class='portraitTiny'/><span>" + $name + "</span></div>");
                    }
                });
        }
        else $('#friendSuggestions').hide();
    });

    $('#friendSuggestions').on("click", ".friendSuggestionsUser", function () {
        $friend = this.id;
        hideAddMemberElements();
        createChip($friend);
    });

    function createChip($friend) {
        $.get('php/groupMemberEdit.php?job=add&friend_id=' + $friend, function (data) {
            if (data == 'added') {
                reload();
            }
        });
    }

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
            if (data == 'deleted') {
                $('#overlay').fadeOut(200);
                $('#popup').fadeOut(200);
                reload();
            }
        });
    });

    $('.leaveGroup').click(function () {
        $('#popupTitle').html("Gruppe verlassen?");
        $('#popupContent').load('subpages/leaveGroupReally.php', function () {
            $('#overlay').fadeIn(200);
            $('#popup').fadeIn(200);
        });
    });

    $('#popupContent').on("click", ".leaveGroupNow", function () {
        $.get('php/groupMemberEdit.php?job=leave', function (data) {
            if (data == 'left') {
                $('#overlay').fadeOut(200);
                $('#popup').fadeOut(200);
                $('.content').html('');
                reloadCurrentChats();
            }
        });
    });

    function reload() {
        $('.content').load('subpages/groupSettings.php');
    }

    function reloadCurrentChats() {
        clearTabsLeft();
        $('#toChats').addClass('navigationActive');

        // Prepare loading effect
        $('#containerLeft #contacts').html('');
        $('.contentLoaderPattern').clone().appendTo($('#containerLeft #contacts'));

        // Auto activate loading effects
        window.setTimeout(function () {
            $('#containerLeft #contacts .contentLoaderPattern').show();
        }, 100);

        window.setTimeout(function () {
            $user_id = $('#currentUser').val();
            $('#containerLeft #contacts').hide();
            $('#containerLeft #contacts').load('subpages/currentChats.php?user_id=' + $user_id);
            $('#chat').ready(function () {
                $('#containerLeft #contacts').show();
            });
        }, 500);
    };

    function clearTabsLeft() {
        $('#toChats').removeClass('navigationActive');
        $('#toContacts').removeClass('navigationActive');
    }
});