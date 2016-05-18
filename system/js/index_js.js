$(document).ready(function () {


    // Notloesung auf zeit !!!!!!!!!!!!!!!!
    window.setTimeout(function() {
        $mePositionX = $('#me').position().left;
        $('#meOptions').offset({left: $mePositionX});
    }, 1000);


    // Connect to websocket
    var wsUri = "ws://10.0.0.17:1414/websocket/server.php";
    websocket = new WebSocket(wsUri);

    // refresh on error
    $('#somethingsWrong').click(function() {
        location.reload();
    });

    websocket.onopen = function (ev) { // connection is open
        $.get('variables/user_id_var.php', function (data) {
            $user_id = data;

            var msg = {
                type: 'user_id',
                message: $user_id
            };

            websocket.send(JSON.stringify(msg));
        });
    }

    websocket.onclose = function (ev) { // connection is open
        /*
        $('#somethingsWrong').fadeIn(200);
        $('#somethingsWrongMessage').addClass('animated zoomIn');
        $('#somethingsWrongMessage').load('subpages/connectionLost.html');
        */
     }

    websocket.onmessage = function (ev) {
        var fullMsg = JSON.parse(ev.data); //PHP sends Json data
        var type = fullMsg.type; //message type
        var msg = fullMsg.message; //message text

        switch (type) {
            case 'note':
                if (msg == 'newFriendRequest') {
                    $('#portraitAlert').show();
                    $('#portraitAlert').addClass('animated zoomIn');
                    $currentReqeusts = $('#enquiries').html();
                    if ($currentReqeusts) {
                        if ($.isNumeric($currentReqeusts)) {
                            $requests = parseInt($currentReqeusts) + 1;
                        }
                        else {
                            $requests = 1;
                        }
                    }
                    else {
                        $requests = 1;
                    }
                    $('#enquiries').html($requests).show();
                }
                break;
            case 'message':
                var $chat_id = fullMsg.chat_id; // id of chat with new message
                $('#chatSound').get(0).play();

                $currentTime = new Date();
                $hours = ("0" + $currentTime.getHours()).slice(-2);
                $minutes = ("0" + $currentTime.getMinutes()).slice(-2);

                $currentChat = $.cookie('chat_id');

                // If chat window opened
                if ($currentChat && $('.content #chat').length && $chat_id == $currentChat) {
                    $('#typing').hide();
                    $('#userstatus').show();

                    var $member_name = fullMsg.member_name;
                    var $member_portrait = fullMsg.member_portrait;

                    // Clear 'no messages' note
                    if ($('#noMessages').length) {
                        $('#content').html('');
                    }

                    if ($member_name) { // checks if incoming is groupmessage
                        if (!$member_portrait) {
                            $member_portrait = 'default.png';
                        }
                        if ($('.content #chat #content .bubble:last').attr('title') == $member_name) {
                            $('.content #chat #content').append("<div class='chatLeft chatMe'><div class='bubbleManuallyLeft tooltip' title='" + $member_name + "'<span class='messageText'>" + msg + "</span><span class='time'>" + $hours + ":" + $minutes + "</span></div></div>");
                        }
                        else {
                            $('.content #chat #content').append("<div class='chatLeft chatMe'><img src='../data/portraits/" + $member_portrait + "' class='img_round' style='margin-right: 10px;'/><div class='bubble tooltip' title='" + $member_name + "'><span class='messageText'>" + msg + "</span><span class='time'>" + $hours + ":" + $minutes + "</span></div></div>");
                        }
                    }
                    else {
                        $portrait = $('#chatInfo .toProfile').html();
                        if ($('.content #chat #content .bubble:last').parent().hasClass("chatLeft")) {
                            $('.content #chat #content').append("<div class='chatLeft chatMe'><div class='bubbleManuallyLeft'><span class='messageText'>" + msg + "</span><span class='time'>" + $hours + ":" + $minutes + "</span></div></div>");
                        }
                        else {
                            $('.content #chat #content').append("<div class='chatLeft chatMe'>" + $portrait + "<div class='bubble'><span class='messageText'>" + msg + "</span><span class='time'>" + $hours + ":" + $minutes + "</span></div></div>");
                        }
                    }
                    $('.chatMe').addClass('animated zoomIn');
                    $('.chatMe').removeClass('chatMe');
                    // Scroll to bottom
                    $content = $('#content');
                    $content.scrollTop($content.prop("scrollHeight"));

                    // Send read
                    var msg = {
                        type: 'read',
                        chat_id: $chat_id
                    };

                    //convert and send data to server
                    websocket.send(JSON.stringify(msg));
                }
                else {
                    $currentNewMessages = $('#' + $chat_id + ' .currentChatsBubble span').html();
                    $animateFlash = false;
                    if ($currentNewMessages) {
                        if ($.isNumeric($currentNewMessages)) {
                            $newMessages = parseInt($currentNewMessages) + 1;
                            if ($newMessages > 1) {
                                $animateFlash = true;
                            }
                        }
                        else {
                            $newMessages = 1;
                        }
                    }
                    else {
                        $newMessages = 1;
                    }

                    $currentNewMessages = $('#' + $chat_id + ' .currentChatsBubble span').html($newMessages);
                    $('#' + $chat_id + ' .currentChatsBubble').show();
                    if ($animateFlash) {
                        setTimeout(function () {
                            $('#' + $chat_id + ' .currentChatsBubble span').addClass('animated flash');
                        }, 250);
                        $('#' + $chat_id + ' .currentChatsBubble span').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                            $('#' + $chat_id + ' .currentChatsBubble span').removeClass('animated flash');

                        });
                    }
                    else {
                        $('#' + $chat_id + ' .currentChatsBubble').addClass('animated zoomIn');
                        $('#' + $chat_id + ' .currentChatsBubble').removeClass('animated zoomOut');
                    }
                }
                if (msg.length > 33) {
                    msg = msg.substr(0, 30) + "..."; // cut to long message
                }
                $('#' + $chat_id + ' .contactInfo .contactLastMessage').html(msg + " <span class='contactLastMessageSent'>" + $hours + ":" + $minutes + "</span>");
                break;
            case 'media':
                var $chat_id = fullMsg.chat_id; // id of chat with new message
                var $media = fullMsg.media; //message text
                $mediaType = $media.substr($media.lastIndexOf('.') + 1);

                $('#chatSound').get(0).play();

                $currentTime = new Date();
                $hours = ("0" + $currentTime.getHours()).slice(-2);
                $minutes = ("0" + $currentTime.getMinutes()).slice(-2);

                $currentChat = $.cookie('chat_id');

                // If chat window opened
                if ($currentChat && $('.content #chat').length && $chat_id == $currentChat) {
                    $('#typing').hide();
                    $('#userstatus').show();

                    var $member_name = fullMsg.member_name;
                    var $member_portrait = fullMsg.member_portrait;

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
                    else {
                        $mediaOutput = "<img src='" + $media + "'/>";
                    }


                    if ($member_name) { // checks if incoming is groupmessage
                        if (!$member_portrait) {
                            $member_portrait = 'default.png';
                        }
                        if ($('.content #chat #content .bubble:last').attr('title') == $member_name) {
                            $('.content #chat #content').append("<div class='chatLeft chatMe'><div class='bubbleManuallyLeft tooltip' title='" + $member_name + "'>" + $mediaOutput + "<span class='time'>" + $hours + ":" + $minutes + "</span></div></div>");
                        }
                        else {
                            $('.content #chat #content').append("<div class='chatLeft chatMe'><img src='../data/portraits/" + $member_portrait + "' class='img_round' style='margin-right: 10px;'/><div class='bubble tooltip' title='" + $member_name + "'>" + $mediaOutput + "<span class='time'>" + $hours + ":" + $minutes + "</span></div></div>");
                        }
                    }
                    else {
                        $portrait = $('#chatInfo .toProfile').html();
                        if ($('.content #chat #content .bubble:last').parent().hasClass("chatLeft")) {
                            $('.content #chat #content').append("<div class='chatLeft chatMe'><div class='bubbleManuallyLeft'>" + $mediaOutput + "<span class='time'>" + $hours + ":" + $minutes + "</span></div></div>");
                        }
                        else {
                            $('.content #chat #content').append("<div class='chatLeft chatMe'>" + $portrait + "<div class='bubble'>" + $mediaOutput + "<span class='time'>" + $hours + ":" + $minutes + "</span></div></div>");
                        }
                    }
                    $('.chatMe').addClass('animated zoomIn');
                    $('.chatMe').removeClass('chatMe');
                    // Scroll to bottom
                    $content = $('#content');
                    $content.scrollTop($content.prop("scrollHeight"));

                    // Send read
                    var msg = {
                        type: 'read',
                        chat_id: $chat_id
                    };

                    //convert and send data to server
                    websocket.send(JSON.stringify(msg));
                }
                else {
                    $currentNewMessages = $('#' + $chat_id + ' .currentChatsBubble span').html();
                    $animateFlash = false;
                    if ($currentNewMessages) {
                        if ($.isNumeric($currentNewMessages)) {
                            $newMessages = parseInt($currentNewMessages) + 1;
                            if ($newMessages > 1) {
                                $animateFlash = true;
                            }
                        }
                        else {
                            $newMessages = 1;
                        }
                    }
                    else {
                        $newMessages = 1;
                    }

                    $currentNewMessages = $('#' + $chat_id + ' .currentChatsBubble span').html($newMessages);
                    $('#' + $chat_id + ' .currentChatsBubble').show();
                    if ($animateFlash) {
                        setTimeout(function () {
                            $('#' + $chat_id + ' .currentChatsBubble span').addClass('animated flash');
                        }, 250);
                        $('#' + $chat_id + ' .currentChatsBubble span').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                            $('#' + $chat_id + ' .currentChatsBubble span').removeClass('animated flash');

                        });
                    }
                    else {
                        $('#' + $chat_id + ' .currentChatsBubble').addClass('animated zoomIn');
                        $('#' + $chat_id + ' .currentChatsBubble').removeClass('animated zoomOut');
                    }
                }

                // Preview in Current Chats on left container
                $('#' + $chat_id + ' .contactInfo .contactLastMessage').html("<i class='material-icons-tiny doneAll'>attachment</i> Datei <span class='contactLastMessageSent'>" + $hours + ":" + $minutes + "</span>");
                break;
            case 'read':
                var $chat_id = fullMsg.chat_id; // id of chat with new message

                $currentChat = $.cookie('chat_id');

                // If chat window opened
                if ($currentChat && $('.content #chat').length && $chat_id == $currentChat) {
                    $('.done').addClass('doneAll');
                    adjustColorsRead();
                }
                break;
            case 'typing':
                var $chat_id = fullMsg.chat_id; // id of chat with new message

                $currentChat = $.cookie('chat_id');

                // If chat window opened
                if ($currentChat && $('.content #chat').length && $chat_id == $currentChat) {
                    $('#typing').html('tippt gerade...');
                    $('#userstatus').hide();
                    $('#typing').show();
                }
                break;
        }
    };

    history.pushState(null, null, location.href);
    window.onpopstate = function (event) {
        history.go(1);
    };
    $('*').load(function () {
        adjustColors();
        window.setTimeout(function () {
            // Place vertical middle
            $totalWidth = $(document).width();
            $totalHeight = $(document).height();
            $containerHeight = $('#container').height();

            $marginTop = ($totalHeight - $containerHeight) / 2;
            $marginTopPercent = 100 * $marginTop / $totalWidth;

            $('#container').css('margin-top', $marginTopPercent + '%').ready(function () {
                $('body').css('overflow', 'hidden');
                $('.bodyBefore').addClass('bodyVisible');
                window.setTimeout(function () {
                    $('body').css('overflow', 'auto');
                }, 600);
            });
        }, 100);
    });

    toCurrentChats();

    $('.contactSearchbox').keyup(function () {
        $searchTag = $('.contactSearchbox').val();
        searchContact($searchTag);
    });

    $('.contactSearchIcon').click(function () {
        $searchTag = $('.contactSearchbox').val();
        searchContact($searchTag);
    });

    function searchContact($searchTag) {
        clearTabsLeft();
        $('#toContacts').addClass('navigationActive');

        // Prepare loading effect
        $('#containerLeft #contacts').html('');
        $('.contentLoaderPattern').clone().appendTo($('#containerLeft #contacts'));

        // Auto activate loading effects
        window.setTimeout(function () {
            $('#containerLeft #contacts .contentLoaderPattern').show();
        }, 100);

        $user_id = $('#currentUser').val();
        $('#containerLeft #contacts').hide();
        $('#containerLeft #contacts').load('subpages/contacts.php?user_id=' + $user_id + '&searchTag=' + $searchTag);
        $('#containerLeft #contacts').ready(function () {
            $('#containerLeft #contacts').show();
        });
    };

    $('#me').click(function () {
        $('#logout, #notifications, #enquiry, #add, #profileSettings').fadeToggle(200);
    });
    $('#logout').click(function () {
        window.location = 'logout.php';
    });

    $('#logout, #notifications, #enquiry, #add, #profileSettings').click(function () {
        $('#logout, #notifications, #enquiry, #add, #profileSettings').fadeToggle(200);
    });
    $('#notifications').click(function () {
        alert('Mute');
        $('#logout, #notifications, #enquiry, #add, #profileSettings').fadeToggle(200);
    });
    $('#enquiry').click(function () {
        $('#popupTitle').html("Freundschaftsanfragen");
        $('#popupContent').load('subpages/enquiry.php', function () {
            $('#overlay').fadeIn(200);
            $('#popup').fadeIn(200);
        });
    });

    // Tabs

    $('#toChats').click(function () {
        toCurrentChats();
    });

    function toCurrentChats() {
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

    $('#toContacts').click(function () {
        clearTabsLeft();
        $('#toContacts').addClass('navigationActive');

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
            $('#containerLeft #contacts').load('subpages/contacts.php?user_id=' + $user_id);
            $('#containerLeft #contacts').ready(function () {
                $('#containerLeft #contacts').show();
            });
        }, 500);
    });

    $('#toChat').click(function () {
        // Prepare loading effect
        $('#containerRight .content').html('');
        $('.contentLoaderPattern').clone().appendTo($('#containerRight .content'));

        // Auto activate loading effect
        window.setTimeout(function () {
            $('#containerRight .content .contentLoaderPattern').show();
        }, 100);

        $('.content').hide();
        $('.content').load('subpages/chat.php', function () {
            $('.content').show();
            clearTabsRight();
            $('#toChat').addClass('navigationActive');
        });
    });
    $('#toMedia').click(function () {
        // Prepare loading effect
        $('#containerRight .content').html('');
        $('.contentLoaderPattern').clone().appendTo($('#containerRight .content'));

        // Auto activate loading effects
        window.setTimeout(function () {
            $('#containerRight .content .contentLoaderPattern').show();
        }, 100);

        window.setTimeout(function () {
            $user_id = $('#currentUser').val();
            $friend_id = this.id;
            $chat_id = $('#currentChat').val();
            $('#currentChat').val($friend_id);
            $('#containerRight .content').hide();
            $('#containerRight .content').load('subpages/mediabox.php?chat_id=' + $chat_id);
            $('#containerRight .content').ready(function () {
                $('#containerRight .content').show();
                clearTabsRight();
                $('#toMedia').addClass('navigationActive');
            });
        }, 500);
    });
    $('#profileSettings').click(function () {
        // Prepare loading effect
        $('#containerRight .content').html('');
        $('.contentLoaderPattern').clone().appendTo($('#containerRight .content'));

        // Auto activate loading effect
        window.setTimeout(function () {
            $('#containerRight .content .contentLoaderPattern').show();
        }, 100);

        window.setTimeout(function () {
            $user_id = $('#currentUser').val();
            $.post("subpages/profile.php",
                {
                    user_id: $user_id
                },
                function (data) {
                    $('.content').html(data);
                });
            clearTabsRight();
            $('#profileSettings').addClass('navigationActive');
        }, 500);
    });

    function clearTabsLeft() {
        $('#toChats').removeClass('navigationActive');
        $('#toContacts').removeClass('navigationActive');
    }

    function clearTabsRight() {
        $('#toChat').removeClass('navigationActive');
        $('#toMedia').removeClass('navigationActive');
        $('#toProfile').removeClass('navigationActive');
    }

    // Adjust colors on menu elements
    function adjustColors() {
        $cookieColor = $.cookie('messengerColor');
        if ($cookieColor) {
            $color = $cookieColor;
            if ($color) {
                $('.tableNavigation td, #containerLeftSearch').css('background-color', $color);
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

            $('.tableNavigation td, #containerLeftSearch').css('background-color', 'rgb(' + $color + ')');
        }
    }

    function adjustColorsRead() {
        $cookieColor = $.cookie('messengerColor');
        if ($cookieColor) {
            $color = $cookieColor;
            if ($color) {
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
            $('#chat .doneAll').css('color', 'rgb(' + $color + ')');
        }
    }

    //add
    $('#add').click(function () {
        $('#popupTitle').html("Kontaktsuche");
        $('#popupContent').load('subpages/addContact.php', function () {
            $('#overlay').fadeIn(200);
            $('#popup').fadeIn(200);
        });
    });

    $('#popupClose').click(function () {
        $('#overlay').fadeOut(200);
        $('#popup').fadeOut(200);
    });


    $('#popupContent').on("click", ".popupClose", function () {
        $('#overlay').fadeOut(200);
        $('#popup').fadeOut(200);
    });

    $('.tooltip').tooltipster({
        contentAsHTML: true,
        animation: 'grow',
        delay: 1000,
        theme: 'tooltipster-custom',
        trigger: 'hover'
    });

    //Ripple Effect
    $(function () {
        $('body').on('click', '.ripple', function (event) {
            event.preventDefault();

            var $div = $('<div/>'),
                btnOffset = $(this).offset(),
                xPos = event.pageX - btnOffset.left,
                yPos = event.pageY - btnOffset.top;


            $div.addClass('ripple-effect');
            var $ripple = $(".ripple-effect");

            $ripple.css("height", $(this).height());
            $ripple.css("width", $(this).height());
            $div
                .css({
                    top: yPos - ($ripple.height() / 2),
                    left: xPos - ($ripple.width() / 2),
                    background: $(this).data("ripple-color")
                })
                .appendTo($(this));

            window.setTimeout(function () {
                $div.remove();
            }, 2000);
        });
    });
});