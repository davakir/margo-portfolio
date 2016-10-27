<?php

namespace AppBundle\Service\Mail;

class Mail
{
	private $_configFile = __DIR__ . '/mail.ini';
	private $_properties;
	private $_messageContent = '';
	private $_headers = '';
	
	public function __construct()
	{
		$this->_properties = parse_ini_file($this->_configFile);
		$this->_headers = "From: margoportfolio@example.com\nReply-To: bella21abyss@gmail.com\nX-Mailer: PHP/" . PHP_VERSION . "\n";
	}
	
	public function addPoint($key, $value)
	{
		if ($key === 'name')
			$this->_properties['subject'] .= $value;
		$this->_messageContent .= $this->_properties[$key] . " : " . $value . "\n";
	}
	
	public function send()
	{
		return mail(
			$this->_properties['to'],
			$this->_properties['subject'],
			$this->_messageContent,
			$this->_headers
		);
	}
}