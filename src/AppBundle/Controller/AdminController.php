<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Yandex\Fotki\FotkiClient;
use Yandex\Fotki\ImageSizes;
use Yandex\Fotki\Models\Album as YandexAlbum;

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
	 * Получение списка альбомов пользователя из Яндекс.Фоток.
	 * Сохранение альбомов вместе с их фотографиями в базу. Выборка альбов из базы, отдача на фронт.
	 *
	 * @Route("/admin/albums/get", name="get_albums")
	 * @param $request
	 * @return Response
	 */
	public function getAlbums(Request $request)
	{
		$albums = [];
		try
		{
			self::$_login = $request->request->get("ya_login");
			
			if (empty(self::$_login))
				return new Response("User login was not set", Response::HTTP_BAD_REQUEST);
			
			/* Get data from Yandex.Fotki */
			$client = new FotkiClient(self::$_login);
			
			$albums = $client->getAlbums();
			
			/* Save albums into database */
			($this->get('dao.albums'))->saveOrUpdateAlbums($albums);
			
			/**
			 * Get albumIds
			 * @var $album YandexAlbum
			 */
			$albumIds = [];
			foreach ($albums as $album)
			{
				$albumIds[] = $album->getId();
			}
			
			/* Save photos into database */
			foreach ($albumIds as $albumId)
			{
				$photos = $client->getAlbumPhotos($albumId);
				($this->get('dao.photos')->saveOrUpdatePhotos($photos, $albumId));
			}
			
			/*
			 * Get data from database.
			 * This caused by situation when some data was deleted in Yandex,
			 * but we still have it for showing to users.
			 */
			$albums = ($this->get('dao.albums'))->getAlbums(self::$_login);
		}
		catch (\Exception $e)
		{
			$logger = $this->get('logger');
			$logger->error($e);
		}
		
		return $this->render(
			'admin/panel-content/photos/albums.html.twig',
			['albums' => $albums, 'size' => ImageSizes::XXS_SIZE]);
	}

    /**
     * Получаем фотографии альбома, который был передан.
     *
     * @Route("/admin/album/{albumId}/photos", name="photos")
     * @param int $albumId
     * @param Request $request
     * @return Response
     */
	public function getPhotos($albumId, Request $request)
    {
    	$albumId = ($this->get('dao.albums'))->getYaAlbumId($albumId);
    	
    	$photos = [];
	    try
	    {
		    self::$_login = $request->request->get("ya_login");
		
		    if (empty(self::$_login))
			    return new Response("User login was not set", Response::HTTP_BAD_REQUEST);
		
		    /* Get data from Yandex.Fotki */
		    $photos = (new FotkiClient(self::$_login))->getAlbumPhotos($albumId);
		    /* Save data into database */
		    ($this->get('dao.photos'))->saveOrUpdatePhotos($photos, $albumId);
		    /*
			 * Get data from database.
			 * This caused by situation when some data was deleted in Yandex,
			 * but we still have it for showing to users.
			 */
		    $photos = ($this->get('dao.photos'))->getPhotos($albumId);
	    }
	    catch (\Exception $e)
	    {
		    $logger = $this->get('logger');
		    $logger->error($e);
	    }

        return $this->render(
            'admin/panel-content/photos/photos.html.twig',
            ['photos' => $photos, 'size' => ImageSizes::XS_SIZE]
        );
    }
    
	/**
	 * Сохраняем данные, загруженные из Яндекс.Фоток и измененные пользователем.
	 * Visible false проставляем только у тех альбомов и фотографий,
	 * которые указал пользователь, остальным проставляем true.
	 * На этом этапе сохраняем в базу фотографии, не загруженные из Яндекс.Фоток.
	 *
	 * @Route("/admin/albums/save", name="save_albums")
	 * @param Request $request
	 * @return Response
	 */
    public function saveData(Request $request)
    {
	    try
	    {
	    	self::$_login = $request->request->get('login');
	    	$unselectedAlbums = $request->request->get('unselectedAlbums');
		    $unselectedPhotos = $request->request->get('unselectedPhotos');
		    
		    if (empty($unselectedAlbums))
		    	$unselectedAlbums = [];
		    if (empty($unselectedPhotos))
		    	$unselectedPhotos = [];
		    
		    ($this->get('dao.albums'))->setAlbumsVisibility($unselectedAlbums);
		    ($this->get('dao.photos'))->setPhotosVisibility($unselectedPhotos);
		    
		    $result = true;
	    }
	    catch (\Exception $e)
	    {
		    $logger = $this->get('logger');
		    $logger->error($e);
	    	$result = false;
	    }
	    
	    $result = [
	    	'result' => $result
	    ];
	    
	    $encoder = new JsonEncoder();
	    return new Response($encoder->encode($result, 'json'), 200, array('Content-Type' => 'application/json'));
    }
}
