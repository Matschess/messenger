$(document).ready(function () {

    // on is required for new videos
    $('#content').on('click', 'video', function () {
        $(this).siblings('.videoControls').removeClass('animated zoomIn, animated zoomOut');
        if (this.paused) {
            // pause all videos
            $('video').each(function () {
                $(this).get(0).pause();
                $(this).siblings('.videoControls').removeClass('animated zoomIn');
            });
            this.play();
            $(this).siblings('.videoControls').addClass('animated zoomOut');
        }
        else {
            this.pause();
            $(this).siblings('.videoControls').addClass('animated zoomIn');
        }
    });
    var $chat_id = $.cookie('chat_id');

    // Send read
    var msg = {
        type: 'read',
        chat_id: $chat_id
    };

    //convert and send data to server
    websocket.send(JSON.stringify(msg));

    // Remove message bubble in currentChats

    $('#' + $chat_id + ' .currentChatsBubble').addClass('animated zoomOut');
    $('#' + $chat_id + ' .currentChatsBubble').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
        $('#' + $chat_id + ' .currentChatsBubble').hide();
        $('#' + $chat_id + ' .currentChatsBubble').removeClass('animated zoomIn');
        $('#' + $chat_id + ' .currentChatsBubble span').html('');
    });

    $(document).keypress(function (e) {
        if (e.keyCode == 13) { // Char-code for enter
            send();
            event.preventDefault();
            return false;
        }
    });

    $('.chatTextBox').keypress(function (e) {
        // Send typing

        var msg = {
            type: 'typing',
            chat_id: $chat_id
        };

        //convert and send data to server
        websocket.send(JSON.stringify(msg));
    });

    $('#send').click(function () {
        send();
    });

    function send() {
        $chat_id = $.cookie('chat_id');
        if ($chat_id) {
            $message = $('.chatTextBox').html();

            if ($message) {
                var msg = {
                    type: 'message',
                    chat_id: $chat_id,
                    message: $message
                };

                //convert and send data to server
                websocket.send(JSON.stringify(msg));

                // clear textbox and close Smiley-chooser
                $('.chatTextBox').html('');
                $('#smileyChooser, #attacher').slideUp(200);

                $currentTime = new Date();
                $hours = $currentTime.getHours();
                $minutes = $currentTime.getMinutes();
                $portrait = $('#mePortrait').attr('src');

                // Clear 'no messages' note
                if ($('#noMessages').length) {
                    $('#content').html('');
                }

                if ($('.content #chat #content .bubble:last').parent().hasClass("chatRight")) {
                    $('#content').append("<div class='chatRight chatMe'><div class='bubbleManuallyRight'>" + $message + "<span class='time'>" + $hours + ":" + $minutes + "</span><i class='material-icons-small done'>done</i></div></div>");
                }
                else {
                    $('#content').append("<div class='chatRight chatMe'><div class='bubble'>" + $message + "<span class='time'>" + $hours + ":" + $minutes + "</span><i class='material-icons-small done'>done</i></div><span id='myPortrait'><img src='" + $portrait + "' class='img_round' style='margin-left: 10px;'/></span></div>");
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

    $('.linkToMember').click(function () {
        $friend_id = this.id.substr(6);
        $('.content').load('subpages/friendsProfile.php?friend_id=' + $friend_id);
    });

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
        $friend_id = this.id;
        $('#overlay').fadeOut(200);
        $('#popup').fadeOut(200);
        $('.content').load('subpages/friendsProfile.php?user_id=' + $user_id + '&friend_id=' + $friend_id);
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
        var form_data = new FormData();
        form_data.append('file', file_data);
        uploadMedia(form_data);
    });

    // File drop
    $("*").on('dragover', function (e) {
        $('#attacher').slideDown(300);
    });
    // Avpod opening media in browser when dropping anywhere
    $("#uploadContainer").on('dragover', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });
    $("#uploadContainer").on('dragenter', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).css('background-color', 'red');
    });
    $("#uploadContainer").on('dragleave', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).css('background-color', '#fff');
    });
    $("#uploadContainer").on('drop', function (e) {
        e.preventDefault();

        var file_data = e.originalEvent.dataTransfer.files[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        uploadMedia(form_data);
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
                    var msg = {
                        type: 'media',
                        chat_id: $chat_id,
                        media: $media
                    };

                    //convert and send data to server
                    websocket.send(JSON.stringify(msg));

                    // clear textbox and close Smiley-chooser
                    $('#smileyChooser, #attacher').slideUp(200);
                    $('.uploadLoader').hide();
                    $('#uploadContainer').show();

                    $currentTime = new Date();
                    $hours = $currentTime.getHours();
                    $minutes = $currentTime.getMinutes();
                    $portrait = $('#mePortrait').attr('src');

                    // Clear 'no messages' note
                    if ($('#noMessages').length) {
                        $('#content').html('');
                    }

                    if ($mediaType == 'mp4') {
                        $mediaOutput = "<div class='mediaVideo'><div class='videoControls'><div class='videoPlayButton'><i class='material-icons'>play_arrow</i></div></div><video><source src='" + $media + "' type='video/mp4'>Your browser does not support the video tag.</video></div>";
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

            }
        });
    }

    $('.tooltip').tooltipster({
        contentAsHTML: true,
        animation: 'grow',
        delay: 250,
        theme: 'tooltipster-custom',
        trigger: 'hover'
    });

    adjustColors();
    // Scroll to bottom
    $content = $('#content');
    $content.scrollTop($content.prop("scrollHeight"));
});