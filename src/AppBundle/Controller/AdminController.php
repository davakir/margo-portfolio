<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;
use AppBundle\Model\Photo;
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
		
		return $this->render(
			'admin/albums.html.twig',
			[
				'albums' => $albums
			]);
	}
	
	/**
	 * Сохранение полученных данных в БД из Яндекса
	 * @param array $data
	 */
	protected function saveData(array $data)
	{
		foreach ($data as $album)
		{
			$albumModel = new Album();
			$albumModel->setYaAlbumId($album['album_id']);
			$albumModel->setAuthor($album['author']);
			$albumModel->setDescription($album['description']);
			$albumModel->setTitle($album['title']);
			foreach ($album['links'] as $link)
			{
				switch ($link['rel']) {
					case 'self':
						$albumModel->setSelfLink($link['href']);
						break;
					case 'cover':
						$albumModel->setCoverLink($link['href']);
						break;
					case 'photos':
						$albumModel->setPhotosLink($link['href']);
				}
			}
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($albumModel);
			
			$em->flush();
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
