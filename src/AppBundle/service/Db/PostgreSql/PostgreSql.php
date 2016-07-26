<?php

namespace Service\Db\PostgreSql;

use Service\Db\AbstractClient;
require_once DEPLOY_PATH . '/db-conf.php';
require_once SERVICE_PATH . '/Db/AbstractClient.php';

class PostgreSql extends AbstractClient
{
	/**
	 * Конфигуратор для подключения к БД
	 * @var
	 */
	protected $dbConfig;
	
	/**
	 * Database server name
	 * @var string
	 */
	protected $dsn;
	
	/**
	 * Объект PDO для соединения с БД
	 * @var
	 */
	protected $pdo;
	
	/**
	 * Функция считывает конфиг подключения к базе,
	 * формирует переменную dsn для последующего создания подключения к БД.
	 *
	 * PostgreSql constructor.
	 */
	public function __construct()
	{
		$this->dbConfig = getConf('heroku');
		
		$this->dsn = $this->dbConfig['db_driver'] .
			':dbname=' . $this->dbConfig['db_name'] .
			';host=' . $this->dbConfig['db_host'] .
			';port=' . $this->dbConfig['db_port'];
	}
	
	/**
	 * Создает подключение к БД
	 *
	 * @return \PDO
	 */
	public function connect()
	{
		if (!$this->pdo)
		{
			$this->pdo = new \PDO(
				$this->dsn,
				$this->dbConfig['db_user'],
				$this->dbConfig['db_password']
			);
		}
		
		return $this->pdo;
	}
	
	public function closeConnect()
	{
		// TODO: Implement closeConnect() method.
	}
}