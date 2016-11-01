<?php

namespace AppBundle\Deploy;

class DbConfiguration
{
	public function getConf($server)
	{
		return parse_ini_file($server . '/conf.ini');
	}
}
