<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Модель фотографии для работы с БД
 *
 * @ORM\Entity
 * @ORM\Table(name="mini_photos")
 */
class MiniPhoto
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
	 * @param mixed $author
	 */
	public function setAuthor($author)
	{
		$this->author = $author;
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
