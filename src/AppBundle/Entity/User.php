<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User model
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
	/**
	 * @ORM\Column(type="integer", name="user_id")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $userId;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $login;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $password;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $lastname;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $firstname;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $midname;
	/**
	 * @ORM\Column(type="smallint", name="is_admin")
	 */
	private $isAdmin;
	/**
	 * @ORM\Column(type="smallint", name="is_default")
	 */
	private $isDefault;
	
	/**
	 * @return mixed
	 */
	public function getUserId()
	{
		return $this->userId;
	}
	
	/**
	 * @param mixed $userId
	 */
	public function setUserId($userId)
	{
		$this->userId = $userId;
	}
	
	/**
	 * @return mixed
	 */
	public function getLogin()
	{
		return $this->login;
	}
	
	/**
	 * @param mixed $login
	 */
	public function setLogin($login)
	{
		$this->login = $login;
	}
	
	/**
	 * @return mixed
	 */
	public function getPassword()
	{
		return $this->password;
	}
	
	/**
	 * @param mixed $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}
	
	/**
	 * @return mixed
	 */
	public function getLastname()
	{
		return $this->lastname;
	}
	
	/**
	 * @param mixed $lastname
	 */
	public function setLastname($lastname)
	{
		$this->lastname = $lastname;
	}
	
	/**
	 * @return mixed
	 */
	public function getFirstname()
	{
		return $this->firstname;
	}
	
	/**
	 * @param mixed $firstname
	 */
	public function setFirstname($firstname)
	{
		$this->firstname = $firstname;
	}
	
	/**
	 * @return mixed
	 */
	public function getMidname()
	{
		return $this->midname;
	}
	
	/**
	 * @param mixed $midname
	 */
	public function setMidname($midname)
	{
		$this->midname = $midname;
	}
	
	/**
	 * @return mixed
	 */
	public function getIsAdmin()
	{
		return $this->isAdmin;
	}
	
	/**
	 * @param mixed $isAdmin
	 */
	public function setIsAdmin($isAdmin)
	{
		$this->isAdmin = $isAdmin;
	}
	
	/**
	 * @return mixed
	 */
	public function getIsDefault()
	{
		return $this->isDefault;
	}
	
	/**
	 * @param mixed $isDefault
	 */
	public function setIsDefault($isDefault)
	{
		$this->isDefault = $isDefault;
	}
}