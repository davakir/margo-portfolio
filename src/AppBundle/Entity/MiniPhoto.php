<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Модель фотографии для работы с БД
 *
 * @ORM\Entity
 * @ORM\Table(name="mini_photos")
 */
class Photo
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $photo_id;
	/**
	 * @ORM\Column(type="integer")
	 */
	private $ya_photo_id;
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
	private $link;
	
	/**
	 * @return mixed
	 */
	public function getYaPhotoId()
	{
		return $this->ya_photo_id;
	}
	
	/**
	 * @param mixed $ya_photo_id
	 */
	public function setYaPhotoId($ya_photo_id)
	{
		$this->ya_photo_id = $ya_photo_id;
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
}