/**
 * Created by maxim-kiryanov on 30.07.16.
 */
$(document).ready(function() {
    var formGetAlbums = $('#photos form#get-albums'),
	    formSaveData = $('#photos form#save-form'),
	    fader = $('.fader');

    formGetAlbums.submit(function(event) {
        $.post(
        	formGetAlbums.attr('action'),
	        formGetAlbums.serialize()
        )
            .done(function(data) {
                var albums = $('#albums');

                albums.html(data);

                albums.find('.collapse')
                    .on('show.bs.collapse', function(event) {
                    	var target = $(event.target);

	                    if (target.find('.row').length == 0)
	                    {
		                    fader.css('display', 'block');
		                    retrievePhotos(target);
	                    }
                    });
	            
	            $('.save-form').css('display', 'block');
	
	            fader.css('display', 'none');
            });
	
	    fader.css('display', 'block');
        event.preventDefault();
    });
	
	formSaveData.submit(function(event) {
		var checkAlbums = $('#albums .album .select-album'),
			unselectedAlbums = [],
			unselectedPhotos = [];
		
		// у каждого альбома проверяем, выбран ли он пользователем
		$.each(checkAlbums, function(i, val) {
			var albumId = checkAlbums[i].id,
				albumPhotos = $('#album-' + albumId.substr(3) + '-photos .container .row .card input');
			
			// если не выбран - ставим в список на удаление видимости
			if (checkAlbums[i].checked === false)
			{
				unselectedAlbums.push(albumId.match(/\d+/g)[0]);
			}
			else
			{
				// иначе проверяем фотографии альбома, выбраны ли они пользователем
				if (albumPhotos.length !== 0)
				{
					$.each(albumPhotos, function(index, value) {
						if (albumPhotos[index].checked === false)
						{
							unselectedPhotos.push(albumPhotos[index].id.substr(3));
						}
					});
				}
			}
			
		});

		$.ajax({
			url: formSaveData.attr('action'),
			method: formSaveData.attr('method'),
			data:
			{
				albumsForUpdate: unselectedAlbums,
				photosForUpdate: unselectedPhotos
			},
			beforeSend: function(e) {
				$('.success-saved-mes').css('display', 'none');
			},
			success: function(data) {
				if (data.result === true)
				{
					console.log(data.result);
					// $('.success-saved-mes').css('display', 'block');
				}
			}
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
	            fader.css('display', 'none');
            });
    };

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