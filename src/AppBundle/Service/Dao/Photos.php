<?php

namespace Service\Dao;

use Db\PostgreSql;

class DaoPhotos
{
	/**
	 * Коннект к базе
	 * @var null
	 */
	protected $connect = null;
	
	public function __construct()
	{
		$this->connect = (new PostgreSql())->connect();
	}
	
	public function getAlbumPhotos($albumId)
	{
		$query = $this->connect->prepare("
			SELECT * FROM photos WHERE to_show = 1 AND album_id = :albumId
		");
		
		$query->bindParam(':albumId', $albumId);
		
		$query->execute();
	}
}
