<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Модель альбома для работы с БД
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AlbumRepository")
 * @ORM\Table(name="albums")
 */
class Album
{
	/**
	 * @ORM\Column(type="integer", name="album_id")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $albumId;
	/**
	 * @ORM\Column(type="integer", name="ya_album_id", unique=true)
	 */
	private $yaAlbumId;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $author;
	/**
	 * @ORM\Column(type="string")
	 */
	private $title;
	/**
	 * @ORM\Column(type="string")
	 */
	private $summary;
	/**
	 * @ORM\Column(type="string", name="link_self", length=3000)
	 */
	private $linkSelf;
	/**
	 * @ORM\Column(type="string", name="link_edit", length=3000, nullable=true)
	 */
	private $linkEdit;
	/**
	 * @ORM\Column(type="string", name="link_photos", length=3000, nullable=true)
	 */
	private $linkPhotos;
	/**
	 * @ORM\Column(type="string", name="link_cover", length=3000, nullable=true)
	 */
	private $linkCover;
	/**
	 * @ORM\Column(type="string", name="link_ymapsml", length=3000, nullable=true)
	 */
	private $linkYmapsml;
	/**
	 * @ORM\Column(type="string", name="link_alternate", length=3000, nullable=true)
	 */
	private $linkAlternate;
	/**
	 * @ORM\Column(type="string", name="img_href", length=3000, nullable=true)
	 */
	private $imgHref;
	/**
	 * @ORM\Column(type="string", name="date_edited", length=255, nullable=true)
	 */
	private $dateEdited;
	/**
	 * @ORM\Column(type="string", name="date_updated", length=255, nullable=true)
	 */
	private $dateUpdated;
	/**
	 * @ORM\Column(type="string", name="date_published", length=255)
	 */
	private $datePublished;
	/**
	 * @ORM\Column(type="integer", name="image_count")
	 */
	private $imageCount;
	/**
	 * @ORM\Column(type="integer")
	 */
	private $visible;
	
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
	public function getYaAlbumId()
	{
		return $this->yaAlbumId;
	}
	
	/**
	 * @param mixed $yaAlbumId
	 */
	public function setYaAlbumId($yaAlbumId)
	{
		$this->yaAlbumId = $yaAlbumId;
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
	public function getLinkSelf()
	{
		return $this->linkSelf;
	}
	
	/**
	 * @param mixed $linkSelf
	 */
	public function setLinkSelf($linkSelf)
	{
		$this->linkSelf = $linkSelf;
	}
	
	/**
	 * @return mixed
	 */
	public function getLinkEdit()
	{
		return $this->linkEdit;
	}
	
	/**
	 * @param mixed $linkEdit
	 */
	public function setLinkEdit($linkEdit)
	{
		$this->linkEdit = $linkEdit;
	}
	
	/**
	 * @return mixed
	 */
	public function getLinkPhotos()
	{
		return $this->linkPhotos;
	}
	
	/**
	 * @param mixed $linkPhotos
	 */
	public function setLinkPhotos($linkPhotos)
	{
		$this->linkPhotos = $linkPhotos;
	}
	
	/**
	 * @return mixed
	 */
	public function getLinkCover()
	{
		return $this->linkCover;
	}
	
	/**
	 * @param mixed $linkCover
	 */
	public function setLinkCover($linkCover)
	{
		$this->linkCover = $linkCover;
	}
	
	/**
	 * @return mixed
	 */
	public function getLinkYmapsml()
	{
		return $this->linkYmapsml;
	}
	
	/**
	 * @param mixed $linkYmapsml
	 */
	public function setLinkYmapsml($linkYmapsml)
	{
		$this->linkYmapsml = $linkYmapsml;
	}
	
	/**
	 * @return mixed
	 */
	public function getLinkAlternate()
	{
		return $this->linkAlternate;
	}
	
	/**
	 * @param mixed $linkAlternate
	 */
	public function setLinkAlternate($linkAlternate)
	{
		$this->linkAlternate = $linkAlternate;
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
	public function getDateEdited()
	{
		return $this->dateEdited;
	}
	
	/**
	 * @param mixed $dateEdited
	 */
	public function setDateEdited($dateEdited)
	{
		$this->dateEdited = $dateEdited;
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
	public function getDatePublished()
	{
		return $this->datePublished;
	}
	
	/**
	 * @param mixed $datePublished
	 */
	public function setDatePublished($datePublished)
	{
		$this->datePublished = $datePublished;
	}
	
	/**
	 * @return mixed
	 */
	public function getImageCount()
	{
		return $this->imageCount;
	}
	
	/**
	 * @param mixed $imageCount
	 */
	public function setImageCount($imageCount)
	{
		$this->imageCount = $imageCount;
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
