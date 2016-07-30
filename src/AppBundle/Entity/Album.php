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
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $album_id;
	/**
	 * @ORM\Column(type="integer")
	 */
	private $ya_album_id;
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
	 * @ORM\Column(type="string", length=255)
	 */
	private $self_link;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $edit_link;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $photos_link;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $cover_link;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $ymapsml_link;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $alternate_link;
	
	/**
	 * @return mixed
	 */
	public function getYaAlbumId()
	{
		return $this->ya_album_id;
	}
	
	/**
	 * @param mixed $ya_album_id
	 */
	public function setYaAlbumId($ya_album_id)
	{
		$this->ya_album_id = $ya_album_id;
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
		return $this->photos_link;
	}
	
	/**
	 * @param string $photos_link
	 */
	public function setPhotosLink($photos_link)
	{
		$this->photos_link = $photos_link;
	}
	
	/**
	 * @return mixed
	 */
	public function getCoverLink()
	{
		return $this->cover_link;
	}
	
	/**
	 * @param string $cover_link
	 */
	public function setCoverLink($cover_link)
	{
		$this->cover_link = $cover_link;
	}
	
	/**
	 * @param string $self_link
	 */
	public function setSelfLink($self_link)
	{
		$this->self_link = $self_link;
	}
	
	/**
	 * @return mixed
	 */
	public function getSelfLink()
	{
		return $this->self_link;
	}
	
}
