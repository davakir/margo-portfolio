<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Модель альбома для работы с БД
 *
 * @ORM\Entity
 * @ORM\Table(name="albums")
 *
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
	 * @ORM\Column(type="integer", name="ya_album_id")
	 */
	private $yaAlbumId;
	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $author;
	/**
	 * @ORM\Column(type="string", length=100)
	 */
	private $title;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $description;
	/**
	 * @ORM\Column(type="string", name="self_link", length=255)
	 */
	private $selfLink;
	/**
	 * @ORM\Column(type="string", name="edit_link", length=255)
	 */
	private $editLink;
	/**
	 * @ORM\Column(type="string", name="photos_link", length=255)
	 */
	private $photosLink;
	/**
	 * @ORM\Column(type="string", name="cover_link", length=255)
	 */
	private $coverLink;
	/**
	 * @ORM\Column(type="string", name="ymapsml_link", length=255)
	 */
	private $ymapsmlLink;
	/**
	 * @ORM\Column(type="string", name="alternate_link", length=255)
	 */
	private $alternateLink;
	/**
	 * @ORM\Column(type="boolean", name="is_neccessary")
	 */
	private $isNeccessary;
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
	 * @param mixed $author
	 */
	public function setAuthor($author)
	{
		$this->author = $author;
	}
	/**
	 * @param mixed $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}
	/**
	 * @param mixed $description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}
	/**
	 * @return mixed
	 */
	public function getPhotosLink()
	{
		return $this->photosLink;
	}
	/**
	 * @param string $photosLink
	 */
	public function setPhotosLink($photosLink)
	{
		$this->photosLink = $photosLink;
	}
	/**
	 * @return mixed
	 */
	public function getCoverLink()
	{
		return $this->coverLink;
	}
	/**
	 * @param string $coverLink
	 */
	public function setCoverLink($coverLink)
	{
		$this->coverLink = $coverLink;
	}
	/**
	 * @param string $selfLink
	 */
	public function setSelfLink($selfLink)
	{
		$this->selfLink = $selfLink;
	}
	/**
	 * @return mixed
	 */
	public function getSelfLink()
	{
		return $this->selfLink;
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
