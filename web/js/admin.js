/**
 * Created by maxim-kiryanov on 30.07.16.
 */
$(document).ready(function() {
    var form = $("#photos form");

    form.submit(function(event) {
        $.post(form.attr('action'), form.serialize())
            .done(function(data) {
                var tbody = $('#albums tbody');

                tbody.html(data);

                tbody.find('.collapse')
                    .on('show.bs.collapse', function(event) {
                        retrievePhotos($(event.target));
                    });
            });

        event.preventDefault();
    });

    var retrievePhotos = function(target) {
        var url = '/admin/album/' + target.attr('data-album-id') + '/photos';
        var query = $.param({
            'ya_login': form.children('#ya_login').val(),
            'album_id': target.attr('data-album-id')
        });

        $.post(url, query)
            .done(function(data) {
                target.children().html(data);
            });
    };

    var getAlbumId = function(value) {
        return value.substring("album-".length, value.length - "-photos".length);
    }
});