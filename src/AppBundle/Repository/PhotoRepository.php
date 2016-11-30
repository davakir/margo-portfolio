<?php

namespace AppBundle\Repository;

use Doctrine;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Photo;
use Yandex\Fotki\Models\Photo as YandexPhoto;

class PhotoRepository extends EntityRepository
{
	/**
	 * @var int
	 */
	protected $_batchSize = 50;
	
	/**
	 * Возвращает фотографии для переданного альбома
	 * @param int $albumId
	 * @param $onlyVisible
	 * @return array
	 * @throws \Exception
	 */
	public function getPhotos($albumId, $onlyVisible = false)
	{
		$conditions = ['albumId' => $albumId];
		if ($onlyVisible)
			$conditions['visible'] = true;
		
		try
		{
			$photos = $this->findBy($conditions);
		}
		catch (\Exception $e)
		{
			throw new \Exception($e);
		}
		
		return $photos;
	}
	
	/**
	 * Сохранение или обновление данных фотографий, полученных из Яндекса,
	 *
	 * @param array $data -- array of YandexPhotos
	 * @param int $albumId
	 * @throws \Exception
	 */
	public function saveOrUpdatePhotos(array $data, $albumId)
	{
		$ids = $this->__getYaPhotoIds($data);
		try
		{
			$photos = $this->findBy(['yaPhotoId' => $ids]);
		}
		catch (\Exception $e)
		{
			throw new \Exception($e);
		}
		
		/** @var $photo Photo */
		$savedPhotoIds = [];
		foreach ($photos as $photo)
		{
			$savedPhotoIds[] = $photo->getYaPhotoId();
		}
		
		/**
		 * Select data to update
		 * @var $photo YandexPhoto
		 */
		$dataToUpdate = [];
		foreach ($data as $key => $photo)
		{
			if (in_array($photo->getId(), $savedPhotoIds))
			{
				$dataToUpdate[] = $photo;
				unset($data[$key]);
			}
		}
		
		/* The other will be inserted */
		$dataToInsert = $data;
		
		try
		{
			$this->__updatePhotos($photos, $dataToUpdate);
			$this->__savePhotos($dataToInsert, $albumId);
		}
		catch (\Exception $e)
		{
			throw new \Exception($e);
		}
	}
	
	/**
	 * Обновление параметра видимости у фотографий.
	 * @param $photos -- array of photoIds
	 * @throws \Exception
	 */
	public function setPhotosVisibility($photos)
	{
		try
		{
			$photosData = $this->findAll();
			
			/** @var $photo Photo */
			foreach ($photosData as $photo)
			{
				$value = in_array($photo->getPhotoId(), $photos) ? 0 : 1;
				
				$photo->setVisible($value);
				$this->getEntityManager()->merge($photo);
			}
			
			$this->getEntityManager()->flush();
		}
		catch (\Exception $e)
		{
			throw new \Exception($e);
		}
	}
	
	/**
	 * @param array $data
	 * @return array
	 */
	private function __getYaPhotoIds(array $data)
	{
		$result = [];
		/** @var YandexPhoto $photo */
		foreach ($data as $photo)
		{
			$result[] = $photo->getId();
		}
		
		return $result;
	}
	
	/**
	 * @param array $photos -- array of YandexPhotos
	 * @param integer $albumId
	 * @throws \Exception
	 */
	private function __savePhotos(array $photos, $albumId)
	{
		if (!empty($photos))
		{
			try
			{
				/** @var $yaPhoto YandexPhoto */
				foreach ($photos as $yaPhoto)
				{
					$album = $this->__createPhoto($yaPhoto, $albumId);
					
					$this->getEntityManager()->persist($album);
				}
				
				$this->getEntityManager()->flush();
			}
			catch (\Exception $e)
			{
				throw new \Exception($e);
			}
		}
	}
	
	/**
	 * @param array $data
	 * @param array $dataToUpdate
	 * @throws \Exception
	 */
	private function __updatePhotos(array $data, array $dataToUpdate)
	{
		if (!empty($data))
		{
			try
			{
				/** @var $photo Photo */
				foreach ($data as $photo)
					/**
					 * Searching for necessary yaPhoto and update
					 * @var $yaPhoto YandexPhoto
					 **/
					foreach ($dataToUpdate as $key => $yaPhoto)
						if ($photo->getYaPhotoId() == $yaPhoto->getId())
						{
							$this->__updatePhoto($photo, $yaPhoto);
							
							$this->getEntityManager()->merge($photo);
							
							unset($dataToUpdate[$key]);
							break;
						}
				
				$this->getEntityManager()->flush();
			}
			catch (\Exception $e)
			{
				throw new \Exception($e);
			}
		}
	}
	
	/**
	 * @param $yaPhoto YandexPhoto
	 * @param integer $albumId
	 * @return Photo
	 */
	private function __createPhoto($yaPhoto, $albumId)
	{
		$photo = new Photo();
		$photo->setYaPhotoId($yaPhoto->getId());
		$photo->setAlbumId($albumId);
		$photo->setAuthor($yaPhoto->getAuthor());
		$photo->setDateCreated($yaPhoto->getDateCreated());
		$photo->setDateUpdated($yaPhoto->getDateUpdated());
		$photo->setTitle($yaPhoto->getTitle());
		$photo->setSummary($yaPhoto->getSummary());
		$photo->setHideOriginal($yaPhoto->isHideOriginal());
		$photo->setAccess($yaPhoto->getAccess());
		$photo->setImgHref($yaPhoto->getImgHref());
		$photo->setLinkAlbum($yaPhoto->getLinkAlbum());
		$photo->setVisible(1);
		
		return $photo;
	}
	
	/**
	 * @param $photo Photo
	 * @param $yaPhoto YandexPhoto
	 */
	private function __updatePhoto($photo, $yaPhoto)
	{
		$photo->setAuthor($yaPhoto->getAuthor());
		$photo->setDateCreated($yaPhoto->getDateCreated());
		$photo->setDateUpdated($yaPhoto->getDateUpdated());
		$photo->setTitle($yaPhoto->getTitle());
		$photo->setSummary($yaPhoto->getSummary());
		$photo->setHideOriginal($yaPhoto->isHideOriginal());
		$photo->setAccess($yaPhoto->getAccess());
		$photo->setImgHref($yaPhoto->getImgHref());
		$photo->setLinkAlbum($yaPhoto->getLinkAlbum());
	}
}
