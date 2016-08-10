/**
 * Created by maxim-kiryanov on 30.07.16.
 */
$(document).ready(function() {
    var form = $("#photos form"),
	    fader = $('.fader');

    form.submit(function(event) {
        $.post(form.attr('action'), form.serialize())
            .done(function(data) {
                var albums = $('#albums');

                albums.html(data);

                albums.find('.collapse')
                    .on('show.bs.collapse', function(event) {
	                    fader.css('display', 'block');
                        retrievePhotos($(event.target));
                    });
	            
	            $('.save-form').css('display', 'block');
	
	            fader.css('display', 'none');
            });
	
	    fader.css('display', 'block');
        event.preventDefault();
    });

    function retrievePhotos(target) {
	    var url = '/admin/album/' + target.attr('data-album-id') + '/photos';
	    var query = $.param({
		    'ya_login': $('#ya_login').val(),
		    'album_id': target.attr('data-album-id')
	    });
	
	    $.post(url, query)
		    .done(function (data) {
			    target.children().html(data);
			    fader.css('display', 'none');
		    });
    }

    function getAlbumId(value) {
        return value.substring("album-".length, value.length - "-photos".length);
    }
});

function checkBtnEnabled(album_id, checked)
{
	var showHideBtn = document.getElementById('album-'+ album_id +'-photos-btn'),
		albumPhotos = document.getElementById('album-'+ album_id +'-photos');

	if (checked == 1)
	{
		showHideBtn.setAttribute('data-toggle', 'collapse');
		showHideBtn.removeAttribute('disabled');
	}
	else
	{
		albumPhotos.style.display = 'none';
		showHideBtn.setAttribute('data-toggle', 'false');
		showHideBtn.setAttribute('disabled', 'true');
	}
}