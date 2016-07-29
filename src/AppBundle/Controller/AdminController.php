<?php

namespace AppBundle\Controller;

use AppBundle\Model\Album;
use AppBundle\Model\Photo;
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
			return "Не задан логин пользователя";
		}
		
		$login = $_POST['ya_login'];
		
		$client = new YandexPhotos($login);
		$albums = $client->getAlbums();
		
		$this->saveData($albums);
		
		return $albums;
	}
	
	/**
	 * Сохранение полученных данных в БД из Яндекса
	 * @param array $data
	 */
	protected function saveData(array $data)
	{
		foreach ($data as $album)
		{
			$album = new Album();
			$album->setYaAlbumId($album['album_id']);
			$album->setAuthor($album['author']);
			$album->setDescription($album['description']);
			$album->setTitle($album['title']);
			foreach ($album['links'] as $link)
			{
				switch ($link['rel']) {
					case 'self':
						$album->setSelfLink($link['href']);
						break;
					case 'cover':
						$album->setCoverLink($link['href']);
						break;
					case 'photos':
						$album->setPhotosLink($link['href']);
				}
			}
			
			
		}
	}
	
	/**
	 * Обновляются только те данные, которые могут меняться через UI
	 * @param array $data
	 */
	public function updateAlbumsForSave(array $data)
	{
		
	}
}
