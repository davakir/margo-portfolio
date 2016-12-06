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
	            // $('#ya_login').attr('disabled', 'disabled');
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
				unselectedAlbums.push(albumId.match(/\d+/g)[0]);
			else
			{
				// иначе проверяем фотографии альбома, выбраны ли они пользователем
				if (albumPhotos.length !== 0)
				{
					$.each(albumPhotos, function(index, value) {
						if (albumPhotos[index].checked === false)
							unselectedPhotos.push(albumPhotos[index].id.substr(3));
					});
				}
			}
			
		});

		$.ajax({
			url: formSaveData.attr('action'),
			method: formSaveData.attr('method'),
			data:
			{
				unselectedAlbums: unselectedAlbums,
				unselectedPhotos: unselectedPhotos,
				login: $('#ya_login').val()
			},
			beforeSend: function(e) {
				$('.success-saved-mes').css('display', 'none');
			},
			success: function(data) {
				if (data.result === true)
				{
					$('.success-saved-mes').css('display', 'block');
				}
			}
		});
		
		event.preventDefault();
		
	});

    var retrievePhotos = function(target) {
        var url = '/admin/album/' + target.attr('data-album-id') + '/photos';
        var query = $.param({
            'ya_login': formGetAlbums.find('input').val(),
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

    function ArticleController() {
    	var container = $('div#articles');
		var toggler = $('a[data-toggle="tab"][href="#posts"]');

		initHandlers();

		function initHandlers() {
			toggler.on('shown.bs.tab', function (e) {
				var url = '/admin/articles';
				$.get(url)
					.done(initArticleTable);
			});

			var buttonCreate = $('button#create-article-button');
			buttonCreate.click(function() {
				window.location.href = '/admin/article/create';
			});
		}

		function initArticleTable(htmlData) {
			container.html(htmlData);
			container.find('button').click(redirectToEdit);
		}

		function redirectToEdit(clickEvent) {
			var id = clickEvent.target.attributes['data-article-id'].value;
			window.location.href = '/admin/article/' + id + '/edit';
		}
	}

	var articleController = new ArticleController();
});
