<?php
/**
 * Created by PhpStorm.
 * User: daryakiryanova
 * Date: 15.11.16
 * Time: 9:01
 */

namespace AppBundle\Deploy\Migration;

use AppBundle\Service\AbstractMigration;

class Migration2 extends AbstractMigration
{
	public function run()
	{
		$res = $this->_connect->query('
			CREATE TABLE IF NOT EXISTS users (
				user_id SERIAL PRIMARY KEY,
				login VARCHAR(255) UNIQUE NOT NULL,
				password VARCHAR(255) NOT NULL,
				lastname VARCHAR(255) DEFAULT NULL,
				firstname VARCHAR(255) DEFAULT NULL,
				midname VARCHAR(255) DEFAULT NULL,
				is_admin SMALLINT,
				is_default SMALLINT
			)
		')->execute();
		
		echo "Выполняются запросы в базу\n";
		echo "Результат выполнения: " . ($res ? 'ok' : 'error occurred') . "\n";
	}
}