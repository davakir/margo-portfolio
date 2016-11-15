function checkBtnEnabled(album_id, checked)
{
	var showHideBtn = document.getElementById('album-'+ album_id +'-photos-btn'),
		albumPhotos = document.getElementById('album-'+ album_id +'-photos');
	
	if (checked == 1)
	{
		showHideBtn.setAttribute('data-toggle', 'collapse');
		showHideBtn.removeAttribute('disabled');
		albumPhotos.removeAttribute('style');
	}
	else
	{
		albumPhotos.style.display = 'none';
		showHideBtn.setAttribute('data-toggle', 'false');
		showHideBtn.setAttribute('disabled', 'true');
	}
}
