<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Модель фотографии для работы с БД
 *
 * @ORM\Entity
 * @ORM\Table(name="photos")
 *
 */
class Photo
{
	/**
	 * @ORM\Column(type="integer", name="photo_id")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $photoId;
	/**
	 * @ORM\Column(type="integer", name="ya_photo_id")
	 */
	private $yaPhotoId;
	/**
	 * @ORM\Column(type="integer", name="album_id")
	 */
	private $albumId;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $author;
	/**
	 * @ORM\Column(type="string", name="date_created", length=255)
	 */
	private $dateCreated;
	/**
	 * @ORM\Column(type="string", name="date_updated", length=255)
	 */
	private $dateUpdated;
	/**
	 * @ORM\Column(type="string")
	 */
	private $title;
	/**
	 * @ORM\Column(type="string")
	 */
	private $summary;
	/**
	 * @ORM\Column(type="boolean", name="hide_original")
	 */
	private $hideOriginal;
	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $access;
	/**
	 * @ORM\Column(type="string", name="img_href", length=3000)
	 */
	private $imgHref;
	/**
	 * @ORM\Column(type="string", name="link_album", length=3000)
	 */
	private $linkAlbum;
	/**
	 * @ORM\Column(type="integer")
	 */
	private $visible;
	
	/**
	 * @return mixed
	 */
	public function getPhotoId()
	{
		return $this->photoId;
	}
	
	/**
	 * @param mixed $photoId
	 */
	public function setPhotoId($photoId)
	{
		$this->photoId = $photoId;
	}
	
	/**
	 * @return mixed
	 */
	public function getYaPhotoId()
	{
		return $this->yaPhotoId;
	}
	
	/**
	 * @param mixed $yaPhotoId
	 */
	public function setYaPhotoId($yaPhotoId)
	{
		$this->yaPhotoId = $yaPhotoId;
	}
	
	/**
	 * @return mixed
	 */
	public function getAlbumId()
	{
		return $this->albumId;
	}
	
	/**
	 * @param mixed $albumId
	 */
	public function setAlbumId($albumId)
	{
		$this->albumId = $albumId;
	}
	
	/**
	 * @return mixed
	 */
	public function getAuthor()
	{
		return $this->author;
	}
	
	/**
	 * @param mixed $author
	 */
	public function setAuthor($author)
	{
		$this->author = $author;
	}
	
	/**
	 * @return mixed
	 */
	public function getDateCreated()
	{
		return $this->dateCreated;
	}
	
	/**
	 * @param mixed $dateCreated
	 */
	public function setDateCreated($dateCreated)
	{
		$this->dateCreated = $dateCreated;
	}
	
	/**
	 * @return mixed
	 */
	public function getDateUpdated()
	{
		return $this->dateUpdated;
	}
	
	/**
	 * @param mixed $dateUpdated
	 */
	public function setDateUpdated($dateUpdated)
	{
		$this->dateUpdated = $dateUpdated;
	}
	
	/**
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * @param mixed $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	/**
	 * @return mixed
	 */
	public function getSummary()
	{
		return $this->summary;
	}
	
	/**
	 * @param mixed $summary
	 */
	public function setSummary($summary)
	{
		$this->summary = $summary;
	}
	
	/**
	 * @return mixed
	 */
	public function getHideOriginal()
	{
		return $this->hideOriginal;
	}
	
	/**
	 * @param mixed $hideOriginal
	 */
	public function setHideOriginal($hideOriginal)
	{
		$this->hideOriginal = $hideOriginal;
	}
	
	/**
	 * @return mixed
	 */
	public function getAccess()
	{
		return $this->access;
	}
	
	/**
	 * @param mixed $access
	 */
	public function setAccess($access)
	{
		$this->access = $access;
	}
	
	/**
	 * @return mixed
	 */
	public function getImgHref()
	{
		return $this->imgHref;
	}
	
	/**
	 * @param mixed $imgHref
	 */
	public function setImgHref($imgHref)
	{
		$this->imgHref = $imgHref;
	}
	
	/**
	 * @return mixed
	 */
	public function getLinkAlbum()
	{
		return $this->linkAlbum;
	}
	
	/**
	 * @param mixed $linkAlbum
	 */
	public function setLinkAlbum($linkAlbum)
	{
		$this->linkAlbum = $linkAlbum;
	}
	
	/**
	 * @return mixed
	 */
	public function getVisible()
	{
		return $this->visible;
	}
	
	/**
	 * @param mixed $visible
	 */
	public function setVisible($visible)
	{
		$this->visible = $visible;
	}
}