<?php
/**
 * Created by PhpStorm.
 * User: daryakiryanova
 * Date: 01.11.16
 * Time: 21:37
 */

namespace AppBundle\Deploy\Migration;

use AppBundle\Service\AbstractMigration;

class Migration1 extends AbstractMigration
{
	public function run()
	{
		$res1 = $this->_connect->query("
			CREATE TABLE IF NOT EXISTS albums (
				album_id SERIAL PRIMARY KEY,
				ya_album_id INT UNIQUE NOT NULL,
				author VARCHAR(50) DEFAULT NULL,
				title VARCHAR(100) DEFAULT NULL,
				description VARCHAR(255) DEFAULT NULL,
				self_link VARCHAR(255) DEFAULT NULL,
				edit_link VARCHAR(255) DEFAULT NULL,
				photos_link VARCHAR(255) DEFAULT NULL,
				cover_link VARCHAR(255) DEFAULT NULL,
				ymapsml_link VARCHAR(255) DEFAULT NULL,
				alternate_link VARCHAR(255) DEFAULT NULL,
				is_neccessary boolean DEFAULT TRUE
			)
		")->execute();
		
		$this->_connect->query('CREATE UNIQUE INDEX IF NOT EXISTS yandex_album_idx ON albums (ya_album_id)')->execute();
		
		$res2 = $this->_connect->query("
			CREATE TABLE IF NOT EXISTS photos (
				photo_id SERIAL PRIMARY KEY,
				ya_photo_id INT UNIQUE NOT NULL,
				album_id INT NOT NULL,
				author VARCHAR(50) DEFAULT NULL,
				link VARCHAR(255) DEFAULT NULL,
				is_neccessary boolean DEFAULT TRUE
			)
		")->execute();
		
		$this->_connect->query('CREATE UNIQUE INDEX IF NOT EXISTS yandex_photo_idx ON photos (ya_photo_id)')->execute();
		
		$res3 = $this->_connect->query("
			CREATE TABLE IF NOT EXISTS mini_photos (
				photo_id SERIAL PRIMARY KEY,
				ya_photo_id INT UNIQUE NOT NULL,
				album_id INT NOT NULL,
				author VARCHAR(50) DEFAULT NULL,
				link VARCHAR(255) DEFAULT NULL,
				is_neccessary boolean DEFAULT TRUE
			)
		")->execute();
		
		$this->_connect->query('CREATE UNIQUE INDEX IF NOT EXISTS yandex_photo_idx ON photos (ya_photo_id)')->execute();
		
		echo "Выполняются запросы в базу\n";
		echo "Результат выполнения: " . ($res1 ? 'ok' : 'error occurred') . "\n";
		echo "Результат выполнения: " . ($res2 ? 'ok' : 'error occurred') . "\n";
		echo "Результат выполнения: " . ($res3 ? 'ok' : 'error occurred') . "\n";
	}
}