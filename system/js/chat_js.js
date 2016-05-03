$(document).ready(function () {
    $chat_id = $.cookie('chat_id');

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
                $('#smileyChooser').slideUp(200);

                $currentTime = new Date();
                $hours = $currentTime.getHours();
                $minutes = $currentTime.getMinutes();
                $portrait = $('.chatRight #myPortrait').html();
                $('#content').append("<div class='chatRight chatMe'><div class='bubble'>" + $message + "<span class='time'>" + $hours + ":" + $minutes + "</span><i class='material-icons-small doneAll'>done</i></div>" + $portrait + "</div>");
                $('.chatMe').addClass('animated zoomIn');
                $('.chatMe').removeClass('chatMe');

                // Scroll to bottom
                $content = $('#content');
                $content.scrollTop($content.prop("scrollHeight"));
            }
        }
    }


    adjustColors();
    // Scroll to bottom
    $content = $('#content');
    $content.scrollTop($content.prop("scrollHeight"));

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

    $('.tooltip').tooltipster({
        contentAsHTML: true,
        animation: 'grow',
        delay: 1000,
        theme: 'tooltipster-custom',
        trigger: 'hover'
    });
});