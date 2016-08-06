<?php

namespace AppBundle\Controller;

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
	public function getAlbums(Request $request)
	{
        $login = $request->request->get("ya_login");
		
		if ($login == null)
		{
			return new Response("User login was not set", Response::HTTP_BAD_REQUEST);
		}
		
		$client = new YandexPhotos($login);
		
		// get all user albums
		$albums = $client->getAlbums();
		
		// get true cover links for albums and set as cover_link
		foreach ($albums as &$album)
		{
			$id = explode('/', rtrim($album['links']['cover_link'], '/'));
			$cover_id = array_pop($id);
			$cover = $client->getPhoto($cover_id, 'XXS');
			$album['links']['cover_link'] = $cover['link'];
		}
		
		// save albums
		($this->get('dao.albums'))->saveAlbums($albums);

		return $this->render(
			'admin/albums.html.twig',
			['albums' => $albums]);
	}

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/admin/album/{albumId}/photos", name="photos")
     */
	public function getPhotos($albumId, Request $request)
    {
        $login = $request->request->get("ya_login");

        if ($login == null || $albumId == null)
        {
            return new Response("User login was not set", Response::HTTP_BAD_REQUEST);
        }

        $client = new YandexPhotos($login);
        $photosId = $client->getPhotosForAlbum($albumId);
	    
	    $photos = [];
	    $miniPhotos = [];
	    foreach ($photosId as $id)
	    {
			$photos[] = $client->getPhoto($id);
		    $miniPhotos[] = $client->getPhoto($id, 'XXS');
	    }
	    
	    // save albums
	    ($this->get('dao.photos'))->savePhotos($albumId, $photos);
	    ($this->get('dao.photos'))->saveMiniPhotos($albumId, $miniPhotos);
	    
        return $this->render(
            'admin/photos.html.twig',
            ['photos' => $miniPhotos]
        );
    }
}
