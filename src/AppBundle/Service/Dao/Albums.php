<?php

namespace AppBundle\Service\Dao;

use Doctrine;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Album;

class Albums
{
	protected $em;

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
		foreach ($data as $album)
		{
			$albumModel = $this->em
				->getRepository('AppBundle:Album')
				->findOneBy(array(
					'ya_album_id' => $album['album_id']
				));
			
			// to select update or insert
			$tmp = $albumModel;
			
			if (!$tmp)
			{
				$albumModel = new Album();
			}
			
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
			
			if (!$tmp)
			{
				$this->em->persist($albumModel);
			}
			
			$this->em->flush();
		}
	}
}
