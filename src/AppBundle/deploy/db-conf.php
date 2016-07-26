<?php

function getConf($server)
{
	return parse_ini_file($server . '/conf.ini');
}
