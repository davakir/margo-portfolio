<?php

namespace Service\Db;

abstract class AbstractClient
{
	abstract function __construct($host);
	
	abstract function connect();
	
	abstract function closeConnect();
}
