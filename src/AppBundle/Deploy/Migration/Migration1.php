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
				author VARCHAR(255) DEFAULT NULL,
				title VARCHAR DEFAULT NULL,
				summary VARCHAR DEFAULT NULL,
				img_href VARCHAR(3000) DEFAULT NULL,
				link_self VARCHAR(3000) DEFAULT NULL,
				link_edit VARCHAR(3000) DEFAULT NULL,
				link_photos VARCHAR(3000) DEFAULT NULL,
				link_cover VARCHAR(3000) DEFAULT NULL,
				link_ymapsml VARCHAR(3000) DEFAULT NULL,
				link_alternate VARCHAR(3000) DEFAULT NULL,
				date_edited VARCHAR(255) DEFAULT NULL,
				date_updated VARCHAR(255) DEFAULT NULL,
				date_published VARCHAR(255) DEFAULT NULL,
				image_count INT DEFAULT NULL,
				visible SMALLINT
			)
		")->execute();
		
		$this->_connect->query('CREATE UNIQUE INDEX IF NOT EXISTS yandex_album_idx ON albums (ya_album_id)')->execute();
		
		$res2 = $this->_connect->query("
			CREATE TABLE IF NOT EXISTS photos (
				photo_id SERIAL PRIMARY KEY,
				ya_photo_id INT UNIQUE NOT NULL,
				album_id INT NOT NULL,
				author VARCHAR(50) DEFAULT NULL,
				date_created VARCHAR(255) DEFAULT NULL,
				date_updated VARCHAR(255) DEFAULT NULL,
				title VARCHAR DEFAULT NULL,
				summary VARCHAR DEFAULT NULL,
				hide_original boolean DEFAULT FALSE,
				access VARCHAR(50) DEFAULT NULL,
				img_href VARCHAR(3000) DEFAULT NULL,
				link_album VARCHAR(3000) DEFAULT NULL,
				visible SMALLINT
			)
		")->execute();
		
		$this->_connect->query('CREATE UNIQUE INDEX IF NOT EXISTS yandex_photo_idx ON photos (ya_photo_id)')->execute();
		
		echo "Выполняются запросы в базу\n";
		echo "Результат выполнения: " . ($res1 ? 'ok' : 'error occurred') . "\n";
		echo "Результат выполнения: " . ($res2 ? 'ok' : 'error occurred') . "\n";
	}
}