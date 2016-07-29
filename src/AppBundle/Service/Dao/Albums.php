<?php

namespace Service\Dao;

use Db\PostgreSql;

class DaoAlbums
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
	
	public function getAllAlbums()
	{
		$query = $this->connect->prepare("
			SELECT * FROM albums WHERE toShow = 1
		");
		
		$query->execute();
	}
	
	public function getAlbum($albumId)
	{
		$query = $this->connect->prepare("
			SELECT * FROM albums WHERE to_show = 1 AND album_id = :albumId
		");
		
		$query->bindParam(':albumId', $albumId);
		
		$query->execute();
	}
}
