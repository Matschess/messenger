$(document).ready(function () {

    onlineStatusRevert();

    // on is required for new videos
    // play video and audio
    $('#content').on('mouseover', '.mediaVideo, .mediaAudio', function () {
        $(this).children('.videoControls, .audioControls').removeClass('animated zoomIn');

        // Mute or unmute the media message
        if ($.cookie('messengerMute') == 'false') {
            $(this).children('video, audio').prop('volume', 1);
        }
        else {
            $(this).children('video, audio').prop('volume', 0);
        }

        $(this).children('video, audio').get(0).play();

        if ($(this).children('audio').length) {
            $(this).children('.audioControls').find('i').html('music_note');
            $(this).children('.audioControls').addClass('animated flipInY, audioPlayingEffect');
        }
        else {
            $(this).children('.videoControls').addClass('animated zoomOut');
        }

        $loader = $('.loaderMessages').html();
        $this = $(this);

        // Watch if video plays after buffering
        $(this).children('video, audio').get(0).addEventListener("playing", function () {
            $('.circular').remove();
        });

        // Watch if video buffers --> loading spinner
        $(this).children('video, audio').get(0).addEventListener("stalled", function () {
            $this.append($loader);
        });
    });

    $('#content').on('mouseleave', '.mediaVideo, .mediaAudio', function () {
        $(this).children('.videoControls, .audioControls').removeClass('animated zoomOut');
        $(this).children('video, audio').get(0).pause();
        if ($(this).children('audio').length) {
            $(this.id + " .audioControls i").html('play_arrow');
            $(this).children('.audioControls').addClass('animated zoomIn');
        }
        $(this).children('.videoControls').addClass('animated zoomIn');
    });

    var $chat_id, $friend_id;
    reloadCookies();

    // Send read
    if ($chat_id) {
        var msg = {
            type: 'read',
            chat_id: $chat_id
        };

        //convert and send data to server
        websocket.send(JSON.stringify(msg));
    }

    // Remove message bubble in currentChats

    $('#' + $chat_id + ' .currentChatsBubble').addClass('animated zoomOut');
    $('#' + $chat_id + ' .currentChatsBubble').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
        $('#' + $chat_id + ' .currentChatsBubble').hide();
        $('#' + $chat_id + ' .currentChatsBubble').removeClass('animated zoomIn');
        $('#' + $chat_id + ' .currentChatsBubble span').html('');
    });

    $(document).keypress(function (e) {
        if (e.keyCode == 13) { // Char-code for enter
            event.preventDefault();
            send();
            return false;
        }
    });


    function onlineStatusRevert() {
        if ($('#userstatus').html() == 'Online') {
            $currentTime = new Date();
            $hours = ("0" + $currentTime.getHours()).slice(-2);
            $minutes = ("0" + $currentTime.getMinutes()).slice(-2);
            window.setTimeout(function () {
                $('#userstatus').slideUp(200);
                window.setTimeout(function () {
                    $('#userstatus').html('Zuletzt online um ' + $hours + ":" + $minutes);
                    $('#userstatus').slideDown(400);
                }, 200)
            }, 10000)
        }
    }

    var $timeLocked = false;
    $('.chatTextBox').keypress(function (e) {
        // Send typing

        if (e.keyCode != 13 != $timeLocked) { // Char-code for enter
            $timeLocked = true;
            if ($chat_id) {
                var msg = {
                    type: 'typing',
                    chat_id: $chat_id
                };

                //convert and send data to server
                websocket.send(JSON.stringify(msg));

                window.setTimeout(function () {
                    $timeLocked = false;
                }, 2000); // to avoid a flood of "I'm typing" to the server
            }
        }
    });

    $('#send').click(function () {
        send();
    });

    function send() {
        if ($chat_id || $friend_id) {
            $message = $('.chatTextBox').html().replace(/&nbsp;/g, ''); // replace &nbsp;'s
            $.trim($message);

            if ($message) {
                $friend_id, $chat_id = '';
                if ($.cookie('chat_id')) {
                    $chat_id = $.cookie('chat_id');
                }
                else {
                    $friend_id = $.cookie('friend_id');
                }
                if ($chat_id) {
                    var msg = {
                        type: 'message',
                        subtype: 'chat',
                        chat_id: $chat_id,
                        message: $message
                    };
                }
                else {
                    var msg = {
                        type: 'message',
                        subtype: 'friend',
                        friend_id: $friend_id,
                        message: $message
                    };
                }

                //convert and send data to server
                websocket.send(JSON.stringify(msg));

                // clear textbox and close Smiley-chooser
                $('.chatTextBox').html('');
                $('#smileyChooser, #attacher').slideUp(200);

                $currentTime = new Date();
                $hours = ("0" + $currentTime.getHours()).slice(-2);
                $minutes = ("0" + $currentTime.getMinutes()).slice(-2);
                $portrait = $('#mePortrait').attr('src');

                // Clear 'no messages' note
                if ($('#noMessages').length) {
                    $('#content').html('');
                }

                if ($('.content #chat #content .bubble:last').parent().hasClass("chatRight")) {
                    $('#content').append("<div class='chatRight chatMe'><div class='bubbleManuallyRight'><span class='messageText'>" + $message + "</span><span class='time'>" + $hours + ":" + $minutes + "</span><i class='material-icons-small done'>done</i></div></div>");
                }
                else {
                    $('#content').append("<div class='chatRight chatMe'><div class='bubble'><span class='messageText'>" + $message + "</span><span class='time'>" + $hours + ":" + $minutes + "</span><i class='material-icons-small done'>done</i></div><span id='myPortrait'><img src='" + $portrait + "' class='img_round' style='margin-left: 10px;'/></span></div>");
                }

                $('.chatMe').addClass('animated zoomIn');
                $('.chatMe').removeClass('chatMe');
                $('.chatMe').removeClass('chatMe');


                if ($message.length > 33) {
                    $message = $message.substr(0, 30) + "..."; // cut to long message
                }
                $('#' + $chat_id + ' .contactInfo .contactLastMessage').html($message + " <span class='contactLastMessageSent'>" + $hours + ":" + $minutes + " <i class='material-icons-tiny doneAll'>done</i></span>");

                // Scroll to bottom
                $content = $('#content');
                $content.scrollTop($content.prop("scrollHeight"));
            }
        }
    }

    $('.chatTextBox').focus();

    $('.chatTextBox').keydown(function () {
        getKeyCode();
    });

    function adjustColors() {
        $cookieColor = $.cookie('messengerColor');
        if ($cookieColor) {
            $color = $cookieColor;
            if ($color) {
                $('#containerRight .tableNavigation td, #chatInfo').css('background-color', $color);
                $('#chat .doneAll').css('color', $color);
            }
        }
        else {
            var sourceImage = document.getElementById("imgForBackground");
            var colorThief = new ColorThief();
            $color = colorThief.getColor(sourceImage);

            if ($color[0] > 200 || $color[1] > 200 || $color[2] > 200) {
                $color[0] = 180;
                $color[1] = 180;
                $color[2] = 180;
            }

            $('#containerRight .tableNavigation td, #chatInfo').css('background-color', 'rgb(' + $color + ')');
            $('#chat .doneAll').css('color', 'rgb(' + $color + ')');
        }
    }

    function getKeyCode(event) {
        event = event || window.event;
        if (event.ctrlKey && event.shiftKey && event.keyCode === 83) {
            showSmileyChooser();
        }
    }

    $('.moreGroupMembers').click(function () {
        $('.content').load('subpages/groupSettings.php');
    });

    $('#optionsIcon').click(function () {
        $('.option').fadeIn(200);
    });

    $(document).click(function (e) {
        if ($(e.target).closest('#optionsIcon').length != 0) return false;
        $('.option').fadeOut(200);
    });

    $('.toProfile').click(function () {
        $user_id = $('#currentUser').val();
        $toFriend_id = this.id;
        $('#overlay').fadeOut(200);
        $('#popup').fadeOut(200);
        $('.content').load('subpages/friendsProfile.php?user_id=' + $user_id + '&friend_id=' + $toFriend_id);
    });

    $('#smiley').click(function () {
        showSmileyChooser();
    });

    function showSmileyChooser() {
        $('#smileyChooser').slideToggle(200);
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
        $('.chatTextBox').append($(this).attr('title') + " ");
        emojify.setConfig({
            img_dir: 'plugins/emojify/images/emoji',  // Directory for emoji images
        });
        emojify.run();
    });

    $('#attach').click(function () {
        $('#attacher').slideToggle(300);
    });

    $('.uploader').change(function () {
        $('#uploadContainer').hide();
        $('.uploadLoader').show();

        var file_data = $('.uploader').prop('files')[0];
        $('.uploader').val('');
        var form_data = new FormData();
        form_data.append('file', file_data);
        uploadMedia(form_data);
    });

    // File drop
    $("*").on('dragover', function (e) {
        $('#attacher').slideDown(300);
    });
    $("*").on('drop', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });
    // Avpod opening media in browser when dropping anywhere
    $("#uploadContainer").on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });
    $("#uploadContainer").on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });
    $("#uploadContainer").on('dragleave', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });
    $("#uploadContainer").on('drop', function (e) {
        e.preventDefault();

        var file_data = e.originalEvent.dataTransfer.files[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        uploadMedia(form_data);

        $('#uploadContainer').hide();
        $('.uploadLoader').show();
    });

    function uploadMedia(form_data) {
        $.ajax({
            url: 'upload.php?job=media&chat_id=' + $chat_id, // point to server-side PHP script
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {
                if (data.substr(0, 9) == "uploaded:") {
                    $media = data.substr(10);
                    $mediaType = data.substr(data.lastIndexOf('.') + 1);

                    //insert media
                    if ($chat_id) {
                        var msg = {
                            type: 'media',
                            chat_id: $chat_id,
                            media: $media
                        };
                    }
                    else {
                        var msg = {
                            type: 'media',
                            friend_id: $friend_id,
                            media: $media
                        };
                    }

                    //convert and send data to server
                    websocket.send(JSON.stringify(msg));

                    // clear textbox and close Smiley-chooser
                    $('#smileyChooser, #attacher').slideUp(200);

                    $currentTime = new Date();
                    $hours = ("0" + $currentTime.getHours()).slice(-2);
                    $minutes = ("0" + $currentTime.getMinutes()).slice(-2);
                    $portrait = $('#mePortrait').attr('src');

                    // Clear 'no messages' note
                    if ($('#noMessages').length) {
                        $('#content').html('');
                    }

                    if ($mediaType == 'mp4') {
                        $mediaOutput = "<div class='mediaVideo'><div class='videoControls'><div class='videoPlayButton'><i class='material-icons'>play_arrow</i></div></div><video><source src='" + $media + "' type='video/mp4'>Your browser does not support the video tag.</video></div>";
                    }
                    else if ($mediaType == 'mp3') {
                        $mediaOutput = "<div class='mediaAudio'><div class='audioControls'><div class='audioPlayButton'><i class='material-icons'>play_arrow</i></div></div><audio><source src='" + $media + "' type='audio/mp3'>Your browser does not support the video tag.</audio></div>";
                    }
                    else if ($mediaType == 'docx') {
                        $media_id = $media.substr(0, $media.lastIndexOf('.'));
                        $mediaOutput = "<a href='php/download_media.php?media_id=" + $media_id + "'><img class='mediaDocumentThn' src='img/word_thn.png'/></a>";
                    }
                    else if ($mediaType == 'xlsx') {
                        $media_id = $media.substr(0, $media.lastIndexOf('.'));
                        $mediaOutput = "<a href='php/download_media.php?media_id=" + $media_id + "'><img class='mediaDocumentThn' src='img/excel_thn.png'/></a>";
                    }
                    else if ($mediaType == 'pptx') {
                        $media_id = $media.substr(0, $media.lastIndexOf('.'));
                        $mediaOutput = "<a href='php/download_media.php?media_id=" + $media_id + "'><img class='mediaDocumentThn' src='img/powerpoint_thn.png'/></a>";
                    }
                    else if ($mediaType == 'pdf') {
                        $media_id = $media.substr(0, $media.lastIndexOf('.'));
                        $mediaOutput = "<a href='php/download_media.php?media_id=" + $media_id + "'><img class='mediaDocumentThn' src='img/pdf_thn.png'/></a>";
                    }
                    else if ($mediaType == 'zip') {
                        $media_id = $media.substr(0, $media.lastIndexOf('.'));
                        $mediaOutput = "<a href='php/download_media.php?media_id=" + $media_id + "'><img class='mediaDocumentThn' src='img/zip_thn.png'/></a>";
                    }
                    else if ($mediaType == 'exe') {
                        $media_id = $media.substr(0, $media.lastIndexOf('.'));
                        $mediaOutput = "<a class='exeHint' href='php/download_media.php?media_id=" + $media_id + "'><img class='mediaDocumentThn' src='img/application_thn.png'/></a>";
                    }
                    else {
                        $mediaOutput = "<img src='" + $media + "'/>";
                    }

                    if ($('.content #chat #content .bubble:last').parent().hasClass("chatRight")) {
                        $('#content').append("<div class='chatRight chatMe'><div class='bubbleManuallyRight'>" + $mediaOutput + "<span class='time'>" + $hours + ":" + $minutes + "</span><i class='material-icons-small done'>done</i></div></div>");
                    }
                    else {
                        $('#content').append("<div class='chatRight chatMe'><div class='bubble'>" + $mediaOutput + "<span class='time'>" + $hours + ":" + $minutes + "</span><i class='material-icons-small done'>done</i></div><span id='myPortrait'><img src='" + $portrait + "' class='img_round' style='margin-left: 10px;'/></span></div>");
                    }

                    $('.chatMe').addClass('animated zoomIn');

                    // Preview in Current Chats on left container
                    $('#' + $chat_id + ' .contactInfo .contactLastMessage').html("<i class='material-icons-tiny doneAll'>attachment</i> Datei <span class='contactLastMessageSent'>" + $hours + ":" + $minutes + " <i class='material-icons-tiny doneAll'>done</i></span>");

                    // Scroll to bottom when media is loaded
                    $('.chatMe').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                        $content = $('#content');
                        $content.animate({scrollTop: $content.prop("scrollHeight")}, 300);
                        $('.chatMe').removeClass('chatMe');
                    });
                }
                else {
                    switch (data) {
                        case "error[size]":
                            $('.attachHeaderErrors').html('Die Datei ist zu groß');
                            break;
                        case "error[filetype]":
                            $('.attachHeaderErrors').html('Dieser Dateityp ist nicht zugelassen');
                            break;
                        default:
                            $('.attachHeaderErrors').html('Ein Fehler trat auf');
                            break;
                    }

                    $('.attachHeaderErrors').fadeIn().delay(2000).fadeOut();
                }
                $('.uploadLoader').hide();
                $('#uploadContainer').show();
            }
        });
    }

    $('#content').on("click", ".exeHint", function () {
        event.preventDefault(); // to avoid direct download before exe hint message
        window.exeDownload = $(this).attr('href');
        $('#popupHeader').html("Pass auf!");

        $('#popupContent').load('subpages/exeHint.php', function () {
            $('#overlay').fadeIn(200);
            $('#popup').fadeIn(200);
        });
    });

    $('#popupContent').on("click", ".downloadExeNow", function () {
        window.location.href = window.exeDownload;
        $('#overlay').fadeOut(200);
        $('#popup').fadeOut(200);
    });

    $('.tooltip').tooltipster({
        contentAsHTML: true,
        animation: 'grow',
        delay: 250,
        theme: 'tooltipster-custom',
        trigger: 'hover'
    });

    function reloadCookies() {
        if ($.cookie('chat_id')) {
            $chat_id = $.cookie('chat_id');
        }
        else {
            $friend_id = $.cookie('friend_id');
        }
    }

    adjustColors();
    // Scroll to bottom
    $content = $('#content');
    $content.scrollTop($content.prop("scrollHeight"));
});