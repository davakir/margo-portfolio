<?php

namespace AppBundle\Service\Mail;

class Mail
{
	private $_configFile = __DIR__ . '/mail.ini';
	private $_properties;
	private $_messageContent = '';
	
	public function __construct()
	{
		$this->_properties = parse_ini_file($this->_configFile);
	}
	
	public function addPoint($key, $value)
	{
		if ($key === 'name')
			$this->_properties['subject'] .= $value;
		else
			$this->_messageContent .= $this->_properties[$key] . " : " . $value . "\n";
	}
	
	public function send()
	{
		return mail(
			$this->_properties['to'],
			$this->_properties['subject'],
			$this->_messageContent
		);
	}
}