<?php

namespace AppBundle\Controller;

use Service\Dao\DaoAlbums;
use Service\Dao\DaoPhotos;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
	 * @Route("/admin/albums", name="albums")
	 */
	public function getDataFromYandex(Request $request)
	{
        $login = $request->request->get("ya_login");

		if ($login == null)
		{
			return new Response("User login was not set", Response::HTTP_BAD_REQUEST);
		}
		
		$client = new YandexPhotos($login);
		
		// get all user albums
		$albums = $client->getAlbums();
		
		// get photos information for each album
//		$photosInfo = [];
//		foreach ($albums as $album)
//		{
//			$photosInfo[$album['album_id']] = $client->getPhotosForAlbum($album['album_id']);
//		}
//
//		// get photos full info
//		$photos = [];
//		foreach ($photosInfo as $albumId => $pictures)
//		{
//			foreach ($pictures as $pic)
//			{
//				$photos[] = [
//					'album_id' => $albumId,
//					'data' => $client->getPhoto($pic['photo_id'], 'orig')
//				];
//			}
//		}
		
		// save albums
//		(new DaoAlbums())->saveAlbums($albums);
		
		return $this->render(
			'admin/albums.html.twig',
			[
				'albums' => $albums,
//				'photos' => $photos
			]);
	}

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/admin/album/{album_id}/photos", name="photos")
     */
	public function getPhotos($album_id, Request $request)
    {
        $login = $request->request->get("ya_login");

        if ($login == null || $album_id == null)
        {
            return new Response("User login was not set", Response::HTTP_BAD_REQUEST);
        }

        $client = new YandexPhotos($login);
        $photos = $client->getPhotosForAlbum($album_id);

        return $this->render(
            'admin/photos.html.twig',
            ['photos' => $photos]
        );
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
