$(document).ready(function () {
    $('.tooltip').tooltipster({
        contentAsHTML: true,
        animation: 'grow',
        delay: 250,
        theme: 'tooltipster-custom',
        trigger: 'hover'
    });

    $('.popupClose').click(function () {
        $('#overlay').fadeOut(200);
        $('#popup').fadeOut(200);
    });

    $('.toProfile').click(function () {
        $user_id = $('#currentUser').val();
        $friend_id = this.id;
        $('#overlay').fadeOut(200);
        $('#popup').fadeOut(200);
        $('.content').load('subpages/friendsProfile.php?user_id=' + $user_id + '&friend_id=' + $friend_id);
    });

    $('.enquiryDiscard').click(function () {
        $id = this.id;
        $.post("php/enquiry_change.php",
            {
                job: 'delete',
                id: $id
            },
            function (data) {
                if (data == 'changed') {
                    $('#popupContent').load('subpages/enquiry.php')
                }
            });
    });

    $('.enquiryAccept').click(function () {
        $id = this.id;
        $.post("php/enquiry_change.php",
            {
                job: 'accept',
                id: $id
            },
            function (data) {
                if (data == 'changed') {
                    $('#popupContent').load('subpages/enquiry.php')
                }
            });
    });
});