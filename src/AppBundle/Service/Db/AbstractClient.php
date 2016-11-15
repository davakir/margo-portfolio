<?php

namespace AppBundle\Service\Db;

abstract class AbstractClient
{
	abstract function __construct();
	
	abstract function connect();
	
	abstract function closeConnect();
}
