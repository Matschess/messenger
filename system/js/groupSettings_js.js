$(document).ready(function () {
    $('script').load(function () {
        $('.chipMemberAdd').click(function () {
            $('.chipMemberAdd').hide();
            $('#groupMemberSearch').show().focus();
        });

        // check if jquery-ui-script (for draggable) has loaded
        memberDrag();
        function memberDrag() {
            $('.chipMember').draggable({
                revert: "invalid",
                revertDuration: 80,
                zIndex: 10000,
                drag: function (event, ui) {
                    // Adapt width of empty chip
                    $('.chipEmptyAdministrators').addClass('chipEmptyAnimations');
                    $('.chipEmptyAdministrators').width(($('#' + this.id).width()));
                    $stopOldAnimation = false;
                    window.setTimeout(function () {
                        if (!$stopOldAnimation) {
                            $('.chipEmptyAdministrators').css('opacity', '1');
                        }
                    }, 300);
                },
                revert: function () {
                    $stopOldAnimation = true;
                    $('.chipEmptyAdministrators').css('opacity', '0');
                    window.setTimeout(function () {
                        $('.chipEmptyAdministrators').width(0);
                    }, 300);
                    return true;
                }
            });
            $('.chipAdministrator').draggable({
                revert: "invalid",
                revertDuration: 80,
                zIndex: 10000,
                drag: function (event, ui) {
                    // Adapt width of empty chip
                    $('.chipEmptyMembers').addClass('chipEmptyAnimations');
                    $('.chipEmptyMembers').width(($('#' + this.id).width()));
                    $stopOldAnimation = false;
                    window.setTimeout(function () {
                        if (!$stopOldAnimation) {
                            $('.chipEmptyMembers').css('opacity', '1');
                        }
                    }, 300);
                },
                revert: function () {
                    $stopOldAnimation = true;
                    $('.chipEmptyMembers').css('opacity', '0');
                    window.setTimeout(function () {
                        $('.chipEmptyMembers').width(0);
                    }, 300);
                    return true;
                }
            });
            $('.chipEmptyMembers').droppable({
                accept: ".chipAdministrator",
                drop: function (event, ui) {
                    $member_id = $(ui.draggable).attr('id').substr(6);
                    $.get('php/groupMemberEdit.php?job=degrade&member_id=' + $member_id, function (data) {
                        if (data == 'degraded') {
                            reload();
                        }
                    });
                }
            });
            $('.chipEmptyAdministrators').droppable({
                accept: ".chip",
                drop: function (event, ui) {
                    $member_id = $(ui.draggable).attr('id').substr(6);
                    $.get('php/groupMemberEdit.php?job=promote&member_id=' + $member_id, function (data) {
                        if (data == 'promoted') {
                            reload();
                        }
                    });
                }
            });
        }

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
            $('#popupHeader').html("Gruppe verlassen?");
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

        $('#groupName').click(function () {
            setTimeout(function () {
                $('#groupNameEdit').focus();
            }, 100);
            $('#groupName').hide();
            $('#groupNameEdit').show();
            $('#groupNameRenewButton').show();
            $('#groupNameCancelButton').show();
        });

        var $currentGroupName = $('#groupNameEdit').val();

        $('#groupNameCancelButton').click(function () {
            $('#groupNameEdit').val($currentGroupName);
            $('#groupNameEdit').hide();
            $('#groupNameRenewButton').hide();
            $('#groupNameCancelButton').hide();
            $('#groupName').show();
        });

        $('#groupNameRenewButton').click(function () {
            $groupname = $('#groupNameEdit').val().trim();
            if($groupname) {
                $.get('php/groupMemberEdit.php?job=editGroupName&groupName=' + $groupname, function (data) {
                    if (data == 'edited') {
                        reload();
                        reloadCurrentChats();
                    }
                });
            }
        });

        $('#portrait').mouseover(function () {
            var vague = $('#portraitImage').Vague({
                intensity: 1,      // Blur Intensity
                forceSVGUrl: false,   // Force absolute path to the SVG filter,
            });
            vague.blur();
        });

        $('#portrait').mouseout(function () {
            var vague = $('#portraitImage').Vague();
            vague.unblur();
        });

        $('#portraitUploadInput').change(function () {
            $('#portraitImage, #portraitOptions').css('opacity', '0');
            $('.portraitLoader').show();
            var file_data = $('#portraitUploadInput').prop('files')[0];
            var form_data = new FormData();
            $user_id = $('#currentUser').val();
            form_data.append('file', file_data);
            $.ajax({
                url: 'upload.php?job=groupPortraitEdit', // point to server-side PHP script
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (data) {
                    $filename = data;
                    $('#portraitImage').attr('src', '../data/groupportraits/' + $filename);
                    $('#portraitImage').load(function () {
                        $('.portraitLoader').hide();
                        $('#portraitImage').css('opacity', '1');
                        $('#portraitOptions').removeAttr('style');
                        adjustColors();
                        reloadCurrentChats();
                    });
                }
            });
        });

        $('#portraitDelete').click(function () {
            $('#portraitImage, #portraitOptions').css('opacity', '0');
            $('.portraitLoader').show();
            $.ajax({
                url: 'php/portrait_delete.php?job=groupPortrait',
                success: function (data) {
                    $('#portraitImage').attr('src', '../data/portraits/default.png');
                    $('#portraitImage').load(function () {
                        $('.portraitLoader').hide();
                        $('#portraitImage').css('opacity', '1');
                        adjustColors();
                        reloadCurrentChats();
                    });
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

        function adjustColors() {
                var sourceImage = document.getElementById("portraitImage");
                var colorThief = new ColorThief();
                $color = colorThief.getColor(sourceImage);

                if ($color[0] > 200 || $color[1] > 200 || $color[2] > 200) {
                    $color[0] = 180;
                    $color[1] = 180;
                    $color[2] = 180;
                }

                $('#containerRight .tableNavigation td').css('background-color', 'rgb(' + $color + ')');
        }
    });
});