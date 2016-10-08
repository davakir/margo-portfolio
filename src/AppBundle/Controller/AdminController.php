<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Yandex\Photos\YandexPhotos;

class AdminController extends Controller
{
	/**
	 * User login for authorization
	 * @var string
	 */
	private static $_login = '';
	
	/**
	 * @Route("/admin", name="admin")
	 * @param $request
	 * @return Response
	 */
	public function indexAction(Request $request)
	{
		return $this->render('admin/index.html.twig');
	}
	
	/**
	 * Получает альбомы пользователя из Яндекс.Фоток. Сохраняет их в базе сайта.
	 * Возвращает список альбомов.
	 *
	 * @Route("/admin/albums/get", name="get_albums")
	 * @param $request
	 * @return Response
	 */
	public function renderAndSaveAlbums(Request $request)
	{
        self:$_login = $request->request->get("ya_login");
		
		if (self::$_login == null)
			return new Response("User login was not set", Response::HTTP_BAD_REQUEST);
		
		$client = new YandexPhotos(self::$_login);
		
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
     * Получаем фотографии альбома (вместе с мини-копиями), который был передан функции.
     * Сохраняем их все в базу сайта, а возвращаем только мини-копии для отрисовки на фронте.
     *
     * @Route("/admin/album/{albumId}/photos", name="photos")
     * @param int $albumId
     * @param Request $request
     * @return Response
     */
	public function renderAndSavePhotos($albumId, Request $request)
    {
        self::$_login = $request->request->get("ya_login");

        if (self::$_login == null || $albumId == null)
        {
            return new Response("User login was not set", Response::HTTP_BAD_REQUEST);
        }

        $client = new YandexPhotos(self::$_login);
        $photosId = $client->getPhotosForAlbum($albumId);
	    
	    $photos = [];
	    $miniPhotos = [];
	    foreach ($photosId as $id)
	    {
			$photos[] = $client->getPhoto($id);
		    $miniPhotos[] = $client->getPhoto($id, 'XXS');
	    }

	    // save photos
	    ($this->get('dao.photos'))->saveNewPhotos($photos, $albumId, 'photos');
	    // save mini-photos
	    ($this->get('dao.photos'))->saveNewPhotos($miniPhotos, $albumId, 'mini_photos');
	    
        return $this->render(
            'admin/photos.html.twig',
            ['photos' => $miniPhotos]
        );
    }
    
	/**
	 * @Route("/admin/albums/save", name="save_albums")
	 * @param Request $request
	 * @return JsonEncoder
	 */
    public function saveData(Request $request)
    {
	    try {
		    ($this->get('dao.albums'))->updateAlbums($request->request->get('albumsForUpdate'));
		    $result = true;
	    }
	    catch (\Exception $e) {
	    	$result = false;
	    }
	    
	    $result = [
	    	'result' => $result
	    ];
	    
	    $encoder = new JsonEncoder();
	    return $encoder->encode($result, 'json');
    }
}
