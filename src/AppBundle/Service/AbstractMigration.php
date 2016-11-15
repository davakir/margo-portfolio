<?php
/**
 * Created by PhpStorm.
 * User: daryakiryanova
 * Date: 01.11.16
 * Time: 21:38
 */

namespace AppBundle\Service;

use AppBundle\Service\Db\PostgreSql\PostgreSql;

/**
 * Класс, описывающий рао
 * Class AbstractMigration
 * @package AppBundle\Service
 */
abstract class AbstractMigration
{
	protected $_connect;
	
	public function __construct()
	{
		if (empty($this->_connect))
			$this->_connect = (new PostgreSql())->connect();
	}
	
	abstract public function run();
}