<?php

define('DEPLOY_PATH', __DIR__ . '/../');
define('SERVICE_PATH',  __DIR__ . '/../../service');
define('VIEWS_PATH',  __DIR__ . '/../../view');

use Service\Db\PostgreSql\PostgreSql;
require_once SERVICE_PATH . '/Db/PostgreSql/PostgreSql.php';

$connect = (new PostgreSql())->connect();
	
$res1 = $connect->query("
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
	
$create = $connect->query('CREATE UNIQUE INDEX IF NOT EXISTS yandex_album_idx ON albums (ya_album_id)')->execute();

$res2 = $connect->query("
	CREATE TABLE IF NOT EXISTS photos (
		photo_id SERIAL PRIMARY KEY,
		ya_photo_id INT UNIQUE NOT NULL,
		album_id INT NOT NULL,
		author VARCHAR(50) DEFAULT NULL,
		link VARCHAR(255) DEFAULT NULL,
		is_neccessary boolean DEFAULT TRUE
	)
")->execute();

$create = $connect->query('CREATE UNIQUE INDEX IF NOT EXISTS yandex_photo_idx ON photos (ya_photo_id)')->execute();

$res3 = $connect->query("
	CREATE TABLE IF NOT EXISTS mini_photos (
		photo_id SERIAL PRIMARY KEY,
		ya_photo_id INT UNIQUE NOT NULL,
		album_id INT NOT NULL,
		author VARCHAR(50) DEFAULT NULL,
		link VARCHAR(255) DEFAULT NULL,
		is_neccessary boolean DEFAULT TRUE
	)
")->execute();

$create = $connect->query('CREATE UNIQUE INDEX IF NOT EXISTS yandex_photo_idx ON photos (ya_photo_id)')->execute();

echo "Выполняются запросы в базу\n";
echo "Результат выполнения: " . ($res1 ? 'ok' : 'error occurred') . "\n";
echo "Результат выполнения: " . ($res2 ? 'ok' : 'error occurred') . "\n";
echo "Результат выполнения: " . ($res3 ? 'ok' : 'error occurred') . "\n";
