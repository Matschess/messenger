$(document).ready(function () {
    $('.toProfile').click(function () {
        $user_id = $('#currentUser').val();
        $friend_id = this.id;
        $('#overlay').fadeOut(200);
        $('#popup').fadeOut(200);
        $('.content').load('subpages/friendsProfile.php?user_id=' + $user_id + '&friend_id=' + $friend_id);
    });

    $('.contact').click(function () {
        // Prepare loading effect
        $('#containerRight .content').html('');
        $('.contentLoaderPattern').clone().appendTo($('#containerRight .content'));

        // Auto activate loading effects
        $('#containerRight .content .contentLoaderPattern').show();

        $chat_id = this.id;
        $('#containerRight .content').hide();
        $('#containerRight .content').load('subpages/chat.php?chat_id=' + $chat_id);
        $('#chat').ready(function () {
            $('#containerRight .content').show();
            clearTabsRight();
            $('#toChat').addClass('navigationActive');
        });
    });

    function clearTabsRight() {
        $('#toChat').removeClass('navigationActive');
        $('#toMedia').removeClass('navigationActive');
        $('#toProfile').removeClass('navigationActive');
    }

    $('#createGroup').click(function () {
        $('#popupHeader').html('Gruppe einrichten');
        $('#popupContent').load('subpages/createGroupName.php', function () {
            $('#overlay').fadeIn(200);
            $('#popup').fadeIn(200);
        });
    });
});