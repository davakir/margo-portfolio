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
	 * User login for authorization
	 * @var string
	 */
	protected static $login = '';
	
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
		if (!self::$login)
		{
			self::$login = $request->request->get("ya_login");
		}
		
		if (self::$login == null)
		{
			return new Response("User login was not set", Response::HTTP_BAD_REQUEST);
		}
		
		$client = new YandexPhotos(self::$login);
		
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
    	if (!self::$login)
	    {
		    self::$login = $request->request->get("ya_login");
	    }

        if (self::$login == null || $albumId == null)
        {
            return new Response("User login was not set", Response::HTTP_BAD_REQUEST);
        }

        $client = new YandexPhotos(self::$login);
        $photosId = $client->getPhotosForAlbum($albumId);
	    
	    $photos = [];
	    $miniPhotos = [];
	    foreach ($photosId as $id)
	    {
			$photos[] = $client->getPhoto($id);
		    $miniPhotos[] = $client->getPhoto($id, 'XS');
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
