$(document).ready(function () {

    $('.groupNameDone').click(function () {
        $('#groupname').removeClass('wrongInput');
        $groupname = $('#groupname').html();
        if ($groupname) {
            $.get("php/group_prepare.php?job=validateGroupName&groupName=" + $groupname, function (data) {
                if (data == "validated") {
                    $('#popupContent').load('subpages/createGroupPortrait.php');
                }
                else {
                    $('#groupname').addClass('wrongInput');
                }
            });
        }
        else {
            window.setTimeout(function () {
                $('#groupname').addClass('wrongInput').delay(500);
            }, 20);
        }
    });

    $('.groupPortraitDone').click(function () {
        $('#popupContent').load('subpages/createGroupMembers.php');
    });

    $('.groupCancel').click(function () {
        $.get('php/group_prepare.php?job=cancelGroup', function (data) {
            if (data == "canceled") {
                $('#overlay').fadeOut(200);
                $('#popup').fadeOut(200);
            }
        });
    });

    $('.createGroupNow').click(function () {
        $groupMembers = '';
        $groupAdministrators = '';
        if ($('.chip').length >= 1) {
            $('.chipAdministrator').each(function () {
                $groupAdministrators = $groupAdministrators + "&groupAdministrators[]=" + $(this).attr('id').substr(6);
            });
            $('.chipMember').each(function () {
                $groupMembers = $groupMembers + "&groupMembers[]=" + $(this).attr('id').substr(6);
            });
            $.get("php/group_prepare.php?job=validateGroupMembers&groupName=" + $groupname + $groupAdministrators + $groupMembers, function (data) {
                if (data == 'validated') {
                    $('#overlay').fadeOut(200);
                    $('#popup').fadeOut(200);
                    reloadCurrentChats();
                }
            });
        }
    });

    $('*').load(function () {
        $('#groupname').focus();
    });

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
        createChip($friend);
    });

    $('#groupMemberSearch').blur(function () {
        $friend = $('#groupMemberSearch').val();
        createChip($friend);
    });

    function createChip($friend) {
        if ($friend != '') {
            $.post("php/findUserForGroup.php",
                {
                    job: 'validate',
                    friend: $friend
                },
                function (data) {
                    if (data != 'error') {
                        data = JSON.parse(data);
                        $user_id = data[0];
                        $username = data[1];
                        $portrait = data[2];

                        // Check if member is already on list
                        if (!$('#member' + $user_id).length) {
                            $('#groupMembers').append('<div id="member' + $user_id + '" class="chip chipMember">' +
                                '<img src="' + $portrait + '"/>' +
                                ' <span>' + $username +
                                ' <i class="material-icons-thin groupMemberDelete">close</i></span>' +
                                '</div>');
                            $('#groupMemberSearch').val('');
                            $('#friendSuggestions').hide();
                            $('#groupMemberSearch').focus();
                            $('.chipMember').draggable({
                                revert: "invalid",
                                revertDuration: 80,
                                zIndex: 10000,
                                drag: function (event, ui) {
                                    // Adapt width of empty chip
                                    $('.chipEmptyAdministrators').width(($('#' + this.id).width()));
                                }
                            });
                            $('.chipEmptyAdministrators').droppable({
                                accept: ".chipMember",
                                drop: function (event, ui) {
                                    $(ui.draggable).css({'top': '0px', 'left': '0px'});
                                    $(ui.draggable).appendTo('#groupAdministrators');
                                    $(ui.draggable).addClass('chipAdministrator');
                                    $(ui.draggable).removeClass('chipMember');
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
                                    $(ui.draggable).addClass('chipMember');
                                    $(ui.draggable).removeClass('chipAdministrator');
                                }
                            });
                        }
                        else {
                            $('#groupMemberSearch').val('');
                            $('#friendSuggestions').hide();
                            $('#groupMemberSearch').focus();
                        }
                    }
                });
        }
    }

    /*
     var vague = $('body').Vague({
     intensity: 6,      // Blur Intensity
     forceSVGUrl: false,   // Force absolute path to the SVG filter,
     });
     vague.blur();
     */

    $('#groupname').bind('DOMSubtreeModified', function () {
        $groupname = $('#groupname').html().replace(/&nbsp;/g, ' ');
        $imagesStartTag = [];
        $imagesEndTag = [];
        for ($i = 0; $i < $groupname.length; $i++) {
            if ($groupname.substr($i, 4) == '<img') {
                $imagesStartTag.push($i);
            }
            if ($groupname.substr($i, 6) == '</img>') {
                $imagesEndTag.push($i + 6);
            }
        }

        for ($j = 0; $j < $imagesStartTag.length; $j++) {
            if ($j > 0) {
                $imageStartTag = $imagesStartTag[$j] - $imagesStartTag[$j - 1] - 2;
                $imageEndTag = $imagesEndTag[$j] - $imagesEndTag[$j - 1] - 2;
            }
            else {
                $imageStartTag = $imagesStartTag[$j];
                $imageEndTag = $imagesEndTag[$j];
            }
            $groupname = $groupname.substr(0, $imageStartTag) + ' ' + $groupname.substr($imageEndTag);
        }

        $leftChars = 60 - $groupname.length;
        $('.charCounter').html($leftChars);

        window.leftChars = $leftChars;
    });

    $('#groupname').keypress(function (e) {
        $leftChars = window.leftChars;
        if ($leftChars <= 0 && ((e.charCode >= 65 && e.charCode <= 122) || e.keyCode == 32)) { // Char-code for letters
            e.preventDefault();
        }
    });

    $('#smiley').click(function () {
        showSmileyChooser();
    });

    $(document).click(function (e) {
        if ($(e.target).closest('#smiley, #smileyChooserBubble').length != 0) return false;
        $('#smileyChooserBubble').fadeOut(200);
    });

    function showSmileyChooser() {
        $('#smileyChooserBubble').fadeToggle(200);
        $('#smileyChooserContainer').load('subpages/smileyChooser/people.html');
        $('.tab').removeClass('tabActive');
        $('#smileyChooserToPeople').addClass('tabActive');
    };

    $('#smileyChooserToPeople').click(function () {
        $('#smileyChooserContainer').load('subpages/smileyChooser/people.html');
        $('.tab').removeClass('tabActive');
        $(this).addClass('tabActive');
    });

    $('#smileyChooserToNature').click(function () {
        $('#smileyChooserContainer').load('subpages/smileyChooser/nature.html');
        $('.tab').removeClass('tabActive');
        $(this).addClass('tabActive');
    });

    $('#smileyChooserToThings').click(function () {
        $('#smileyChooserContainer').load('subpages/smileyChooser/things.html');
        $('.tab').removeClass('tabActive');
        $(this).addClass('tabActive');
    });

    $('#smileyChooserToVehicles').click(function () {
        $('#smileyChooserContainer').load('subpages/smileyChooser/vehicles.html');
        $('.tab').removeClass('tabActive');
        $(this).addClass('tabActive');
    });

    $('#smileyChooserToSigns').click(function () {
        $('#smileyChooserContainer').load('subpages/smileyChooser/signs.html');
        $('.tab').removeClass('tabActive');
        $(this).addClass('tabActive');
    });

    $('#smileyChooserContainer').on("click", "img", function () {
        $('#groupname').append($(this).attr('title') + " ");
        emojify.setConfig({
            img_dir: 'plugins/emojify/images/emoji',  // Directory for emoji images
        });
        emojify.run();
    });

    $('#groupMembers').on("click", ".groupMemberDelete", function () {
        $(this).parents('.chip').remove();
        $('#groupMemberSearch').focus();
    });

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

    // upload group portrait
    $('#groupPortraitUploader').change(function () {
        var file_data = $('#groupPortraitUploader').prop('files')[0];
        $('#groupPortraitUploader').val('');
        var form_data = new FormData();
        form_data.append('file', file_data);
        uploadGroupPortrait(form_data);
    });

    function uploadGroupPortrait(form_data) {
        $('#groupPortraitImg').hide();
        $('.groupPortraitLoader').show();
        $.ajax({
            url: 'upload.php?job=groupPortrait', // point to server-side PHP script
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                if (data) {
                    $newImage = '../data/groupportraits/' + data;
                    $('#groupPortraitImg').attr('src', $newImage);
                    $('#groupPortraitImg').load(function () {
                        $('.groupPortraitLoader').hide();
                        $('#groupPortraitImg').show();
                    });
                }
            }

        });
    }
});