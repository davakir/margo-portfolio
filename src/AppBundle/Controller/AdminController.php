<?php

namespace AppBundle\Controller;

use Service\Dao\DaoAlbums;
use Service\Dao\DaoPhotos;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Yandex\Photos\YandexPhotos;

class AdminController extends Controller
{
	/**
	 * @Route("/admin", name="admin")
	 */
	public function indexAction(Request $request)
	{
		return $this->render('admin/index.html.twig');
	}
	
	/**
	 * Обрабатывает POST-запрос
	 *
	 * Получаем альбомы пользователя из Яндекс.Фоток
	 * @Route("/admin/albums/get", name="albums")
	 */
	public function getDataFromYandex()
	{
		if (!isset($_POST['ya_login']))
		{
			return "User login was not set";
		}
		
		$login = $_POST['ya_login'];
		
		$client = new YandexPhotos($login);
		
		// get all user albums
		$albums = $client->getAlbums();
		
		// get photos information for each album
		$photosInfo = [];
		foreach ($albums as $album)
		{
			$photosInfo[$album['album_id']] = $client->getPhotosForAlbum($album['album_id']);
		}
		
		// get photos full info
		$photos = [];
		foreach ($photosInfo as $albumId => $pictures)
		{
			foreach ($pictures as $pic)
			{
				$photos[] = [
					'album_id' => $albumId,
					'data' => $client->getPhoto($pic['photo_id'], 'orig')
				];
			}
		}
		
		// save albums
		(new DaoAlbums())->saveAlbums($albums);
		
		return $this->render(
			'admin/albums.html.twig',
			[
				'albums' => $albums,
				'photos' => $photos
			]);
	}
	
	/**
	 * @param int $albumId
	 */
	protected function savePhotos(int $albumId)
	{
		
	}
	
	/**
	 * Обновляются только те данные, которые могут меняться через UI
	 * @param array $data
	 */
	public function updateAlbumsForSave(array $data)
	{
		
	}
}
