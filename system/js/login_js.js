$(document).ready(function () {

    // Place vertical middle
    $totalWidth = $(document).width();
    $totalHeight = $(document).height();
    $loginboxHeight = $('#loginbox').height();

    $marginTop = ($totalHeight - $loginboxHeight) / 2;
    $marginTopPercent = 100 * $marginTop / $totalWidth;

    $('#loginbox').css('margin-top', $marginTopPercent + '%').ready(function() {
        $('#loginbox').fadeIn(200);
        setTimeout(function () {
            $('#loginbox').removeClass('loginboxPre');
        }, 50);
    });

    $(document).keypress(function(e) {
        if(e.charCode >= 65 && e.charCode <= 122 && !$('#password').is(":focus")) { // Char-code for letters (A-Z, a-z)
            $('#username').focus();
        }
        if(e.keyCode == 13) { // Char-code for enter
            loginNow();
        }
    });

    $('#loginNow').click(function () {
        loginNow();
    });

    function loginNow() {
        $username = $('#username').val();
        $password = $('#password').val();
        if ($username.trim() && $password.trim()) {
            $.post("php/login_check.php",
                {
                    username: $username,
                    password: $password
                },
                function (data) {
                    if (data == 'access') {
                        window.location = 'index.php';
                    }
                    else if (data == 'locked') {
                        $('#overlay').fadeIn(200);
                        $('#popup').fadeIn(200);
                    }
                    else {
                        wiggle();
                    }
                });
        }
    }

    $('.closePopup').click(function () {
        $('#overlay').fadeOut(200);
        $('#popup').fadeOut(200);
        $('#username, #password').val('');
    });

    function wiggle() {
        setTimeout(function () {
            $('#loginNow').css("margin-left", "-5px");
            setTimeout(function () {
                $('#loginNow').css("margin-left", "5px");
                setTimeout(function () {
                    $('#loginNow').css("margin-left", "-5px");
                    setTimeout(function () {
                        $('#loginNow').css("margin-left", "5px");
                        setTimeout(function () {
                            $('#loginNow').css("margin-left", "0px");
                        }, 50);
                    }, 50);
                }, 50);
            }, 50);
        }, 50);
    };
});