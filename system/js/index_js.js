$(document).ready(function () {
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

        $chat_id = $.cookie('chat_id');
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

            if($color[0] > 200 || $color[1] > 200 || $color[2] > 200) {
                $color[0] = 180;
                $color[1] = 180;
                $color[2] = 180;
            }

            $('.tableNavigation td, #containerLeftSearch').css('background-color', 'rgb(' + $color + ')');
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