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
	 * Сохранение фотографий, полученных из Яндекса данных,
	 * но не находящихся в базе сервиса.
	 * По умолчанию функция работает с моделью Photos, может работать с MiniPhotos.
	 * Если фотография в базе уже есть, она обновлению не подлежит,
	 * т.к. ее поля не могут изменены по объективным причинам:
	 *  1) фотография может принадлежать только одному альбому,
	 *  2) ее идентификатор в рамках Яндекс.Фоток не изменится,
	 *  3) ссылка на фото не изменится, исходя из п.2
	 *
	 * @param array $data
	 * @param int $albumId
	 * @param string $model
	 */
	public function saveNewPhotos(array $data, $albumId, $model = 'photos')
	{
		$appBundle = ($model == 'photos') ? 'AppBundle:Photo' : 'AppBundle:MiniPhoto';
		
		// получаю из базы данные по фотографиям (если они есть)
		$photos = $this->em->getRepository($appBundle)
			->findBy([
				'yaPhotoId' => array_column($data, 'photo_id')
			]);
		
		// удаляю из набора данных фотографии, которые уже есть в базе
		foreach ($photos as $photo)
		{
			foreach ($data as $key => $ph)
			{
				if ($ph['photo_id'] == $photo->getYaPhotoId())
				{
					unset($data[$key]);
				}
				
				break;
			}
		}
		
		// выполняем вставку, если есть что вставлять
		if (!empty($data))
		{
			$batchSize = 30;
			foreach ($data as $key => $photo)
			{
				$photoModel = ($model == 'photos') ? new Photo() : new MiniPhoto();
				
				$photoModel->setYaPhotoId($photo['photo_id']);
				$photoModel->setAlbumId($albumId);
				$photoModel->setAuthor($photo['author']);
				$photoModel->setLink($photo['link']);
				$photoModel->setIsNeccessary('true');
				
				$this->em->persist($photoModel);
				
				if (($key % $batchSize) == 0)
				{
					$this->em->flush();
					$this->em->clear();
				}
			}
			
			$this->em->flush();
		}
	}
	
	/**
	 * @param array $photos
	 */
	public function updatePhotosVisibility(array $photos)
	{
		// получаю из базы данные по альбомам (если они есть)
		$photosData = $this->em->getRepository('AppBundle:Photo')
			->findBy([
				'yaPhotoId' => $photos
			]);
		
		$miniPhotosData = $this->em->getRepository('AppBundle:MiniPhoto')
			->findBy([
				'yaPhotoId' => $photos
			]);
		
		foreach ($photosData as $photo)
		{
			$photo->setIsNeccessary(false);
			$this->em->merge($photo);
		}
		$this->em->flush();
		foreach ($miniPhotosData as $photo)
		{
			$photo->setIsNeccessary(false);
			$this->em->merge($photo);
		}
		$this->em->flush();
	}
}
