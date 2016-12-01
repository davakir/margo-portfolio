<?php

namespace AppBundle\Repository;

use Doctrine;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Album;
use Yandex\Fotki\Models\Album as YandexAlbum;

class AlbumRepository extends EntityRepository
{
	/**
	 * Возвращает список всех доступных для отображения альбомов из базы.
	 *
	 * @param $userName string
	 * @param $onlyVisible
	 * @return array The Album objects
	 * @throws \Exception
	 */
	public function getAlbums($userName, $onlyVisible = false)
	{
		$conditions = ['author' => $userName];
		if ($onlyVisible)
			$conditions['visible'] = 1;
		
		try
		{
			$albums = $this->findBy($conditions);
		}
		catch (\Exception $e)
		{
			throw new \Exception($e);
		}
		
		return $albums;
	}
	
	/**
	 * Возвращает информацию об одном альбоме.
	 *
	 * @param $albumId
	 * @return object The Album object
	 * @throws \Exception
	 */
	public function getAlbum($albumId)
	{
		try
		{
			return $this->findOneBy(['yaAlbumId' => $albumId]);
		}
		catch (\Exception $e)
		{
			throw new \Exception($e);
		}
	}
	
	/**
	 * Сохранение/обновление полученных из Яндекса данных в БД.
	 *
	 * @param array $data -- array of YandexAlbums
	 * @throws \Exception
	 */
	public function saveOrUpdateAlbums(array $data)
	{
		$ids = $this->__getYaAlbumIds($data);
		try
		{
			$albums = $this->findBy(['yaAlbumId' => $ids]);
		}
		catch (\Exception $e)
		{
			throw new \Exception($e);
		}
		
		/** @var $album Album */
		$savedAlbumIds = [];
		foreach ($albums as $album)
		{
			$savedAlbumIds[] = $album->getYaAlbumId();
		}
		
		/**
		 * Select data to update
		 * @var $album YandexAlbum
		 */
		$dataToUpdate = [];
		foreach ($data as $key => $album)
		{
			if (in_array($album->getId(), $savedAlbumIds))
			{
				$dataToUpdate[] = $album;
				unset($data[$key]);
			}
		}
		
		/* The other will be inserted */
		$dataToInsert = $data;
		
		try
		{
			$this->__updateAlbums($albums, $dataToUpdate);
			$this->__saveAlbums($dataToInsert);
		}
		catch (\Exception $e)
		{
			throw new \Exception($e);
		}
	}
	
	/**
	 * @param $albumIds
	 * @throws \Exception
	 */
	public function setAlbumsVisibility($albumIds)
	{
		try
		{
			$albums = $this->findAll();
			
			/** @var $album Album */
			foreach ($albums as $album)
			{
				$value = in_array($album->getAlbumId(), $albumIds) ? 0 : 1;
				
				$album->setVisible($value);
				$this->getEntityManager()->merge($album);
			}
			
			$this->getEntityManager()->flush();
		}
		catch (\Exception $e)
		{
			throw new \Exception($e);
		}
	}
	
	/**
	 * @param $albumId
	 * @return integer
	 */
	public function getYaAlbumId($albumId)
	{
		return $this
			->findOneBy(['albumId' => $albumId])
			->getYaAlbumId();
	}
	
	/**
	 * @param array $albums -- array of YandexAlbums
	 * @return array
	 */
	private function __getYaAlbumIds(array $albums)
	{
		/**
		 * @var $album YandexAlbum
		 */
		$result = [];
		foreach ($albums as $album)
		{
			$result[] = $album->getId();
		}

		return $result;
	}
	
	/**
	 * @param array $data -- array of YandexAlbums
	 * @throws \Exception
	 */
	private function __saveAlbums(array $data)
	{
		if (!empty($data))
		{
			try
			{
				/** @var $yaAlbum YandexAlbum */
				foreach ($data as $yaAlbum)
				{
					$album = $this->__createAlbum($yaAlbum);
					
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
	 * @param array $data -- albums from database
	 * @param array $dataToUpdate -- array of YandexAlbums
	 * @throws \Exception
	 */
	private function __updateAlbums(array $data, array $dataToUpdate)
	{
		if (!empty($data))
		{
			try
			{
				/** @var $album Album */
				foreach ($data as $album)
					/**
					 * Searching for necessary yaAlbum and update
					 * @var $yaAlbum YandexAlbum
					 **/
					foreach ($dataToUpdate as $key => $yaAlbum)
						if ($album->getYaAlbumId() == $yaAlbum->getId())
						{
							$this->__updateAlbum($album, $yaAlbum);
							
							$this->getEntityManager()->merge($album);
							
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
	 * @param $yaAlbum YandexAlbum
	 * @return Album
	 */
	private function __createAlbum($yaAlbum)
	{
		$album = new Album();
		$album->setYaAlbumId($yaAlbum->getId());
		$album->setAuthor($yaAlbum->getAuthor());
		$album->setSummary($yaAlbum->getSummary());
		$album->setTitle($yaAlbum->getSummary());
		$album->setImageCount($yaAlbum->getImageCount());
		$album->setDatePublished($yaAlbum->getDatePublished());
		$album->setDateUpdated($yaAlbum->getDateUpdated());
		$album->setImgHref($yaAlbum->getImgHref());
		$album->setLinkSelf($yaAlbum->getLinkSelf());
		$album->setLinkPhotos($yaAlbum->getLinkPhotos());
		$album->setLinkCover($yaAlbum->getLinkCover());
		$album->setLinkEdit($yaAlbum->getLinkEdit());
		$album->setVisible(1);
		
		return $album;
	}
	
	/**
	 * @param $album Album
	 * @param $yaAlbum YandexAlbum
	 */
	private function __updateAlbum($album, $yaAlbum)
	{
		$album->setAuthor($yaAlbum->getAuthor());
		$album->setSummary($yaAlbum->getSummary());
		$album->setTitle($yaAlbum->getSummary());
		$album->setImageCount($yaAlbum->getImageCount());
		$album->setDatePublished($yaAlbum->getDatePublished());
		$album->setDateUpdated($yaAlbum->getDateUpdated());
		$album->setImgHref($yaAlbum->getImgHref());
		$album->setLinkSelf($yaAlbum->getLinkSelf());
		$album->setLinkPhotos($yaAlbum->getLinkPhotos());
		$album->setLinkCover($yaAlbum->getLinkCover());
		$album->setLinkEdit($yaAlbum->getLinkEdit());
	}
}
