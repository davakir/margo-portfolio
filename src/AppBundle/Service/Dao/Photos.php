<?php

namespace AppBundle\Service\Dao;

use Doctrine;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Photo;
use AppBundle\Entity\MiniPhoto;

class Photos
{
	/**
	 * @var EntityManager
	 */
	private $_em;
	
	/**
	 * @var int
	 */
	private $_batchSize = 30;
	
	/**
	 * Photos constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->_em = $em;
	}
	
	/**
	 * Возвращает фотографии для переданного альбома
	 * @param int $albumId
	 * @param string $model
	 * @return array
	 */
	public function getPhotos($albumId, $model)
	{
		$appBundle = ($model == 'photos') ? 'AppBundle:Photo' : 'AppBundle:MiniPhoto';
		
		$data = $this->_em->getRepository($appBundle)
			->findBy([
				'albumId' => $albumId
			]);
		
		/**
		 * @var $photo Photo
		 */
		foreach ($data as $key => $photo)
			if (!$photo->getIsNeccessary())
				unset($data[$key]);
		
		return $data;
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
		$photos = $this->_em->getRepository($appBundle)
			->findBy([
				'yaPhotoId' => array_column($data, 'photo_id')
			]);
		
		/**
		 * Удаляю из набора данных фотографии, которые уже есть в базе
		 * @var $photo Photo
		 */
		foreach ($photos as $photo)
		{
			foreach ($data as $key => $ph)
			{
				if ($ph['photo_id'] == $photo->getYaPhotoId())
					unset($data[$key]);
				break;
			}
		}
		
		// выполняем вставку, если есть что вставлять
		if (!empty($data))
		{
			foreach ($data as $key => $photo)
			{
				$photoModel = ($model == 'photos') ? new Photo() : new MiniPhoto();
				
				$photoModel->setYaPhotoId($photo['photo_id']);
				$photoModel->setAlbumId($albumId);
				$photoModel->setAuthor($photo['author']);
				$photoModel->setLink($photo['link']);
				$photoModel->setIsNeccessary('true');
				
				$this->_em->persist($photoModel);
				
				if (($key % $this->_batchSize) == 0)
				{
					$this->_em->flush();
					$this->_em->clear();
				}
			}
			
			$this->_em->flush();
		}
	}
	
	/**
	 * Обновление параметра видимости у фотографий (нормальных и мини-версий)
	 * @param array $photos
	 */
	public function updatePhotosVisibility(array $photos)
	{
		$photosData = $this->_em->getRepository('AppBundle:Photo')
			->findBy(['yaPhotoId' => $photos]);
		
		$miniPhotosData = $this->_em->getRepository('AppBundle:MiniPhoto')
			->findBy(['yaPhotoId' => $photos]);
		
		/**
		 * @var $photo Photo
		 */
		foreach ($photosData as $photo)
		{
			$photo->setIsNeccessary(false);
			$this->_em->merge($photo);
		}
		$this->_em->flush();
		
		/**
		 * @var $photo MiniPhoto
		 */
		foreach ($miniPhotosData as $photo)
		{
			$photo->setIsNeccessary(false);
			$this->_em->merge($photo);
		}
		$this->_em->flush();
	}
}
