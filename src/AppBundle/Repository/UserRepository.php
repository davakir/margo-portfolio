<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
	/**
	 * Get default user whose albums should be used.
	 * @return object
	 */
	public function getDefaultUser()
	{
		return $this->findOneBy(['isDefault' => 1]);
	}
	
	/**
	 * @param $user User
	 */
	public function createUser(User $user)
	{
		$this->getEntityManager()->persist($user);
		$this->getEntityManager()->flush();
	}
}