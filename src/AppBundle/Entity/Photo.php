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
	 * @ORM\Column(type="string", length=50)
	 */
	private $author;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $link;
	/**
	 * @ORM\Column(type="boolean", name="is_neccessary")
	 */
	private $isNeccessary;
	
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
	public function getLink()
	{
		return $this->link;
	}
	
	/**
	 * @param mixed $link
	 */
	public function setLink($link)
	{
		$this->link = $link;
	}
	
	/**
	 * @return mixed
	 */
	public function getIsNeccessary()
	{
		return $this->isNeccessary;
	}
	
	/**
	 * @param mixed $isNeccessary
	 */
	public function setIsNeccessary($isNeccessary)
	{
		$this->isNeccessary = $isNeccessary;
	}
}