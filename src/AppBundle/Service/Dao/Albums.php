<?php

namespace AppBundle\Service\Dao;

use Doctrine;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Album;
use Yandex\Fotki\Models\Album as YandexAlbum;

class Albums
{
	/**
	 * @var EntityManager
	 */
	protected $_em;
	/**
	 * @var string
	 */
	private $__entityBundle = 'AppBundle:Album';
	
	/**
	 * Albums constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->_em = $em;
	}
	
	/**
	 * Возвращает список всех доступных для отображения альбомов из базы.
	 *
	 * @param $user string
	 * @param $onlyVisible
	 * @return array
	 * @throws \Exception
	 */
	public function getAlbums($user, $onlyVisible = false)
	{
		$conditions = ['author' => $user];
		if ($onlyVisible)
			$conditions['visible'] = true;
		
		try
		{
			$albums = $this->_em->getRepository($this->__entityBundle)
				->findBy($conditions);
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
	 * @return array
	 * @throws \Exception
	 */
	public function getAlbum($albumId)
	{
		try
		{
			return $this->_em->getRepository($this->__entityBundle)
				->findOneBy(['yaAlbumId' => $albumId]);
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
			$albums = $this->_em->getRepository($this->__entityBundle)
				->findBy(['yaAlbumId' => $ids]);
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
			$albums = $this->_em->getRepository($this->__entityBundle)->findAll();
			
			/** @var $album Album */
			foreach ($albums as $album)
			{
				$value = in_array($album->getAlbumId(), $albumIds) ? 0 : 1;
				
				$album->setVisible($value);
				$this->_em->merge($album);
			}
			
			$this->_em->flush();
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
		return $this->_em->getRepository($this->__entityBundle)
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
					
					$this->_em->persist($album);
				}
				
				$this->_em->flush();
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
							
							$this->_em->merge($album);
							
							unset($dataToUpdate[$key]);
							break;
						}
				
				$this->_em->flush();
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
