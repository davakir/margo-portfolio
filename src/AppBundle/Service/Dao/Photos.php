<?php

namespace AppBundle\Service\Dao;

use Doctrine;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Photo;
use AppBundle\Entity\MiniPhoto;

class Photos
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
	public function savePhotos($albumId, array $data)
	{
		foreach ($data as $photo)
		{
			$photoModel = $this->em
				->getRepository('AppBundle:Photo')
				->findOneBy(array(
					'yaPhotoId' => $photo['photo_id']
				));
			
			// to select update|insert
			$tmp = $photoModel;
			
			if (!$tmp)
			{
				$photoModel = new Photo();
			}
			
			$photoModel->setYaPhotoId($photo['photo_id']);
			$photoModel->setAlbumId($albumId);
			$photoModel->setAuthor($photo['author']);
			$photoModel->setLink($photo['link']);
			
			if (!$tmp)
			{
				$this->em->persist($photoModel);
			}
			
			$this->em->flush();
		}
	}
	
	/**
	 * Сохранение/обновление полученных из Яндекса данных в БД
	 * @param array $data
	 */
	public function saveMiniPhotos($albumId, array $data)
	{
		foreach ($data as $photo)
		{
			$photoModel = $this->em
				->getRepository('AppBundle:MiniPhoto')
				->findOneBy(array(
					'yaPhotoId' => $photo['photo_id']
				));
			
			// to select update|insert
			$tmp = $photoModel;
			
			if (!$tmp)
			{
				$photoModel = new MiniPhoto();
			}
			
			$photoModel->setYaPhotoId($photo['photo_id']);
			$photoModel->setAlbumId($albumId);
			$photoModel->setAuthor($photo['author']);
			$photoModel->setLink($photo['link']);
			
			if (!$tmp)
			{
				$this->em->persist($photoModel);
			}
			
			$this->em->flush();
		}
	}
}
