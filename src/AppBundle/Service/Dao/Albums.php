<?php

namespace Service\Dao;

use AppBundle\Entity\Album;

class DaoAlbums
{
	/**
	 * Сохранение полученных данных в БД из Яндекса
	 * @param array $data
	 */
	public function saveAlbums(array $data)
	{
		foreach ($data as $album)
		{
			$albumModel = new Album();
			$albumModel->setYaAlbumId($album['album_id']);
			$albumModel->setAuthor($album['author']);
			$albumModel->setDescription($album['description']);
			$albumModel->setTitle($album['title']);
			
			foreach ($album['links'] as $rel => $href)
			{
				switch ($rel) {
					case 'self_link':
						$albumModel->setSelfLink($href);
						break;
					case 'cover_link':
						$albumModel->setCoverLink($href);
						break;
					case 'photos_link':
						$albumModel->setPhotosLink($href);
				}
			}
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($albumModel);
			
			$em->flush();
		}
	}
}
