$(document).ready(function () {
    $('#optionsIcon').click(function () {
        $('.option').fadeIn(200);
    });

    $(document).click(function (e) {
        if ($(e.target).closest('#optionsIcon').length != 0) return false;
        $('.option').fadeOut(200);
    });

    $('.thumbnail').click(function () {
        var sourceImage = document.getElementById('img' + this.id);
        var colorThief = new ColorThief();
        $color = colorThief.getColor(sourceImage);
        $('#' + this.id).css('background-color', 'rgb(' + $color + ')');

        var vague = $('#' + this.id + ' img').Vague({
            intensity: 6,      // Blur Intensity
            forceSVGUrl: false,   // Force absolute path to the SVG filter,
        });
        if ($('#' + this.id + ' img').is('.thumbnailMarked')) {
            vague.unblur();
        }
        else {
            vague.blur();
        }
        $('#' + this.id + ' img').toggleClass("thumbnailMarked");
        $('#' + this.id + ' i').toggle();
        if ($(".thumbnailMarked").length) {
            $('#mediaboxOptions').fadeIn(150);
        }
        else {
            $('#mediaboxOptions').fadeOut(150);
        }
    });

    $('.thumbnail').dblclick(function () {
        $media_id = this.id;
        window.location = 'php/download_media.php?media_id[0]=' + $media_id + '&chat_id=' + $chat_id;
    });

    $('#downloadAllMedia').click(function () {
        $chat_id = 1;
        window.location = 'php/download_media.php?everything=true';
    });

    $('#deleteMedia').click(function () {
        $marked = markedMedia();
        $chat_id = 1;
        $media_ids = '';
        $number = $marked.length;
        if ($number) {
            $.each($marked, function (index, value) {
                $media_ids = $media_ids + "&media_id[]=" + value;
            });

            if ($number == 1) {
                $('#popupTitle').html("Datei löschen?");
            }
            else {
                $('#popupTitle').html("Datein löschen?");
            }

            window.mediaToDelete = $media_ids;

            $('#popupContent').load('subpages/deleteMediaReally.php?number=' + $marked.length, function () {
                $('#overlay').fadeIn(200);
                $('#popup').fadeIn(200);
            });


        }
    });

    $('#popupContent').on("click", ".deleteMediaNow", function () {
        $media_ids = window.mediaToDelete;

        $.get('php/delete_media.php?' + $media_ids, function (data) {
            if (data == 'deleted') {
                $('#overlay').fadeOut(200);
                $('#popup').fadeOut(200);
                reloadMediabox();
            }
        });
    });

    function reloadMediabox() {
        $chat_id = 1;
        $.post("subpages/mediabox.php",
            {
                chat_id: $chat_id
            },
            function (data) {
                $('.content').html(data);
            });
    };


    $('#downloadMedia').click(function () {
        $marked = markedMedia();
        $chat_id = 1;
        $media_ids = '';
        $.each($marked, function (index, value) {
            $media_ids = $media_ids + "&media_id[]=" + value;
        });
        window.location = 'php/download_media.php?chat_id=' + $chat_id + $media_ids;
    });

    function markedMedia() {
        marked = [];
        $('.thumbnailMarked').each(function (index) {
            marked.push($(this).parent().attr('id'));
        });
        return marked;
    };
});