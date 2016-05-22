$(document).ready(function () {
    adjustColors();

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
            url: 'upload.php?job=portrait', // point to server-side PHP script
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                $filename = data;
                $('#mePortrait').attr('src', '../data/portraits/' + $filename);
                $('#portraitImage').attr('src', '../data/portraits/' + $filename);
                $('#portraitImage').load(function () {
                    $('.portraitLoader').hide();
                    $('#portraitImage').css('opacity', '1');
                    $('#portraitOptions').removeAttr('style');
                    adjustColors();
                });
            }
        });
    });

    $('#portraitDelete').click(function () {
        $('#portraitImage, #portraitOptions').css('opacity', '0');
        $('.portraitLoader').show();
        $.ajax({
            url: 'php/portrait_delete.php',
            success: function (data) {
                $('#mePortrait').attr('src', '../data/portraits/default.png');
                $('#portraitImage').attr('src', '../data/portraits/default.png');
                $('#portraitImage').load(function () {
                    $('.portraitLoader').hide();
                    $('#portraitImage').css('opacity', '1');

                    adjustColors();
                });
            }
        });
    });

    $('#myStatus, #myStatusEmpty').click(function () {
        setTimeout(function () {
            $('#myStatusEdit').focus();
        }, 100);
        $('#quote').hide();
        $('#myStatus, #myStatusNone').hide();
        $('#myStatusEditButton').hide();
        $('#myStatusEdit').show();
        $('#myStatusRenewButton').show();
        $('#myStatusCancelButton').show();
    });

    var $currentStatus = $('#myStatusEdit').val();

    $('#myStatusCancelButton').click(function () {
        $('#myStatusEdit').val($currentStatus);
        $('#myStatusEdit').hide();
        $('#myStatusRenewButton').hide();
        $('#myStatusCancelButton').hide();
        $('#quote').show();
        $('#myStatus, #myStatusNone').show();
    });

    $('#myStatusRenewButton').click(function () {
        $statustext = $('#myStatusEdit').val().trim();
        $.post("php/profile_save.php",
            {
                job: 'status',
                statustext: $statustext
            },
            function() {
                reloadProfile();
            });
    });

    function reloadProfile() {
        $user_id = $('#currentUser').val();
        $.post("subpages/profile.php",
            {
                user_id: $user_id
            },
            function (data) {
                $('.content').html(data);
            });
    };

    $('.selectBoxHeader').click(function () {
        $('.selectBoxOption').fadeToggle(100);
    });

    $('.selectBoxOption').click(function () {
        $option = $('#' + this.id).html();
        $('.selectBoxOption').hide();
        $id = this.id;
        $user_id = $('#currentUser').val();
        $isPublic = false;
        if (this.id == 'public') {
            $isPublic = true;
        }
        $.post("php/profile_save.php",
            {
                job: 'visibility',
                user_id: $user_id,
                isPublic: $isPublic
            },
            function (data) {
                if (data == 'updated') {
                    $('.selectBoxOption').removeClass('selectBoxSelected');
                    $('#' + $id).addClass('selectBoxSelected');
                }
            });

    });

    $('#automaticColors').click(function () {
        $user_id = $('#currentUser').val();
        if ($('#automaticColors').is(":checked")) {
            $color = "";
        }
        else {
            $color = "#27AE60";
        }
        $.post("php/color_change.php",
            {
                user_id: $user_id,
                color: $color
            },
            function (data) {
                if (data == 'changed') {
                    $('#currentColor').val($color);
                    reloadProfile();
                }
            });
    });

    $('#myColor').click(function () {
        $('#popupTitle').html("Farbe auswählen");
        $('#popupContent').load('subpages/colorChooser.php', function () {
            $('#overlay').fadeIn(200);
            $('#popup').fadeIn(200);
        });
    });

    function adjustColors() {
        $cookieColor = $.cookie('messengerColor');
        if ($cookieColor) {
            $color = $cookieColor;
            if ($color) {
                $('.tableNavigation td, #containerLeftSearch, .selectBoxHeader').css('background-color', $color);
            }
        }
        else {
            var sourceImage = document.getElementById("mePortrait");
            var colorThief = new ColorThief();
            $color = colorThief.getColor(sourceImage);

            if ($color[0] > 200 || $color[1] > 200 || $color[2] > 200) {
                $color[0] = 180;
                $color[1] = 180;
                $color[2] = 180;
            }

            $('.tableNavigation td, #containerLeftSearch, .selectBoxHeader').css('background-color', 'rgb(' + $color + ')');
        }
    }

    $('.tooltip').tooltipster({
        contentAsHTML: true,
        animation: 'grow',
        delay: 250,
        theme: 'tooltipster-custom',
        trigger: 'hover'
    });
});