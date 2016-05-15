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
        if ($(this).hasClass('exeHint')) {
            window.exeMediaId = this.id;
            $('#popupTitle').html("Pass auf!");

            $('#popupContent').load('subpages/exeHint.php', function () {
                $('#overlay').fadeIn(200);
                $('#popup').fadeIn(200);
            });
        }
        else {
            var $chat_id = $.cookie('chat_id');
            $media_id = this.id;
            window.location = 'php/download_media.php?media_id[0]=' + $media_id + '&chat_id=' + $chat_id;
        }
    });

    $('#popupContent').on("click", ".downloadExeNow", function () {
        $media_id = window.exeMediaId;
        window.location = 'php/download_media.php?media_id[0]=' + $media_id + '&chat_id=' + $chat_id;
        $('#overlay').fadeOut(200);
        $('#popup').fadeOut(200);
    });

    $('#downloadAllMedia').click(function () {
        window.location = 'php/download_media.php?everything=true';
    });

    $('#deleteAllMedia').click(function () {
        $('#popupTitle').html("Medienbox leeren?");

        window.deleteAllMedia = true;

        $('#popupContent').load('subpages/deleteMediaReally.php?everything=true', function () {
            $('#overlay').fadeIn(200);
            $('#popup').fadeIn(200);
        });
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

            window.deleteAllMedia = false;
            window.mediaToDelete = $media_ids;

            $('#popupContent').load('subpages/deleteMediaReally.php?number=' + $marked.length, function () {
                $('#overlay').fadeIn(200);
                $('#popup').fadeIn(200);
            });


        }
    });

    $('#popupContent').on("click", ".deleteMediaNow", function () {
        if (window.deleteAllMedia) {
            $param = 'everything=true';
        }
        else {
            $param = window.mediaToDelete;
        }

        $.get('php/delete_media.php?' + $param, function (data) {
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
        if ($marked.length == 1 && $('#' + $marked[0]).hasClass('exeHint')) {
            window.exeMediaId = $marked[0];
            $('#popupTitle').html("Pass auf!");

            $('#popupContent').load('subpages/exeHint.php', function () {
                $('#overlay').fadeIn(200);
                $('#popup').fadeIn(200);
            });
        } else {
            $chat_id = 1;
            $media_ids = '';
            $.each($marked, function (index, value) {
                $media_ids = $media_ids + "&media_id[]=" + value;
            });
            window.location = 'php/download_media.php?chat_id=' + $chat_id + $media_ids;
        }
    });

    function markedMedia() {
        marked = [];
        $('.thumbnailMarked').each(function (index) {
            marked.push($(this).parent().attr('id'));
        });
        return marked;
    };

    $('.tooltip').tooltipster({
        contentAsHTML: true,
        animation: 'grow',
        delay: 250,
        theme: 'tooltipster-custom',
        trigger: 'hover'
    });
});