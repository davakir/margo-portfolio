<?php
/**
 * Created by PhpStorm.
 * User: daryakiryanova
 * Date: 15.11.16
 * Time: 10:10
 */

namespace AppBundle\Service\Dao;

use Doctrine\ORM\EntityManager;

class Users
{
	/**
	 * @var EntityManager
	 */
	protected $_em;
	/**
	 * @var string
	 */
	private $__entityBundle = 'AppBundle:User';
	
	/**
	 * Users constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->_em = $em;
	}
	
	/**
	 * Get default user whose albums should be used.
	 * @return string
	 */
	public function getDefaultUser()
	{
		return $this->_em->getRepository($this->__entityBundle)
			->findOneBy(['isDefault' => 1])
			->getLogin();
	}
}