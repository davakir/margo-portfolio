<?php

namespace AppBundle\Service\Dao;

use Doctrine;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Album;

class Albums
{
	/**
	 * @var EntityManager
	 */
	protected $em;
	
	/**
	 * Albums constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}
	
	/**
	 * Сохранение/обновление полученных из Яндекса данных в БД
	 * @param array $data
	 */
	public function saveAlbums(array $data)
	{
		// получаю из базы данные по альбомам (если они есть)
		$albums = $this->em->getRepository('AppBundle:Album')
			->findBy([
				'yaAlbumId' => array_column($data, 'album_id')
			]);
		
		$savedAlbumIds = [];
		/**
		 * @var $album Album
		 */
		foreach ($albums as $album)
			$savedAlbumIds[] = $album->getYaAlbumId();
		
		// выбираю альбомы для обновления
		$dataToUpdate = [];
		foreach ($data as $key => $album)
		{
			if (in_array($album['album_id'], $savedAlbumIds))
			{
				$dataToUpdate[] = $album;
				unset($data[$key]);
			}
		}
		
		// выполняем обновление, если есть ранее сохраненные альбомы
		if (!empty($dataToUpdate))
		{
			/**
			 * @var $album Album
			 */
			foreach ($albums as $album)
			{
				// ищем нужные данные для обновления
				foreach ($dataToUpdate as $key => $item)
				{
					if ($album->getYaAlbumId() == $item['album_id'])
					{
						$album->setAuthor($item['author']);
						$album->setDescription($item['description']);
						$album->setTitle($item['title']);
						
						foreach ($item['links'] as $rel => $href)
						{
							switch ($rel) {
								case 'self_link':
									$album->setSelfLink($href);
									break;
								case 'cover_link':
									$album->setCoverLink($href);
									break;
								case 'photos_link':
									$album->setPhotosLink($href);
							}
						}
						
						$this->em->merge($album);
						
						// после первого совпадения переходим к обновлению
						// следующего альбома
						unset($dataToUpdate[$key]);
						break;
					}
				}
			}
			
			$this->em->flush();
		}
		
		// остальное будем вставлять
		$dataToInsert = $data;
		
		if (!empty($dataToInsert))
		{
			foreach ($dataToInsert as $album)
			{
				$albumModel = new Album();
				
				$albumModel->setYaAlbumId($album['album_id']);
				$albumModel->setAuthor($album['author']);
				$albumModel->setDescription($album['description']);
				$albumModel->setTitle($album['title']);
				
				foreach ($album['links'] as $rel => $href)
				{
					switch ($rel) {
						case 'self_link':
							$albumModel->setSelfLink($href);
							break;
						case 'cover_link':
							$albumModel->setCoverLink($href);
							break;
						case 'photos_link':
							$albumModel->setPhotosLink($href);
					}
				}
				
				$this->em->persist($albumModel);
			}
			
			$this->em->flush();
		}
	}
	
	/**
	 * @param array $albums
	 */
	public function updateAlbumsVisibility(array $albums)
	{
		// получаю из базы данные по альбомам (если они есть)
		$albumsData = $this->em->getRepository('AppBundle:Album')
			->findBy(['yaAlbumId' => $albums]);
		
		/**
		 * @var $album Album
		 */
		foreach ($albumsData as $album)
		{
			$album->setIsNeccessary(false);
			$this->em->merge($album);
		}
		
		$this->em->flush();
	}
	
	/**
	 * Возвращает список всех доступных для отображения альбомов из базы
	 * @return array
	 */
	public function getAlbums()
	{
		$albums = $this->em->getRepository('AppBundle:Album')->findAll();
		
		/**
		 * @var $album Album
		 */
		foreach ($albums as $key => $album)
			if (!$album->getIsNeccessary())
				unset($albums[$key]);
	}
	
	/**
	 * Возвращает информацию об одном альбоме
	 * @param $albumId
	 * @return array
	 */
	public function getAlbum($albumId)
	{
		return $this->em->getRepository('AppBundle:Album')
			->findBy(['yaAlbumId' => $albumId]);
	}
}
