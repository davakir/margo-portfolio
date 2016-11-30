<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User model
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements UserInterface, \Serializable
{
	/**
	 * @ORM\Column(type="integer", name="user_id")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\Column(name="login", type="string", length=255, unique=true)
	 */
	private $username;
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $password;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $lastname;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $firstname;
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $midname;
	/**
	 * @ORM\Column(type="smallint", name="is_admin", options={"default":0})
	 */
	private $isAdmin;
	/**
	 * @ORM\Column(type="smallint", name="is_default", options={"default":0})
	 */
	private $isDefault;
	/**
	 * @ORM\Column(type="smallint", name="is_active", options={"default":1})
	 */
	private $isActive;
	
	public function __construct()
	{
		$this->setIsAdmin(0);
		$this->setIsDefault(0);
		$this->setIsActive(1);
	}
	
	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}
	
	/**
	 * @return mixed
	 */
	public function getUsername()
	{
		return $this->username;
	}
	
	/**
	 * @param mixed $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
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
	
	/**
	 * @return mixed
	 */
	public function getIsActive()
	{
		return $this->isActive;
	}
	
	/**
	 * @param mixed $isActive
	 */
	public function setIsActive($isActive)
	{
		$this->isActive = $isActive;
	}
	
	public function eraseCredentials()
	{
		
	}
	
	public function serialize()
	{
		return serialize(array(
			$this->id,
			$this->username,
			$this->password,
		));
	}
	
	/**
	 * @param $serialized
	 * @see \Serializable::unserialize()
	 */
	public function unserialize($serialized)
	{
		list (
			$this->id,
			$this->username,
			$this->password,
		) = unserialize($serialized);
	}
	
	public function getRoles()
	{
		return array('ROLE_USER', 'ROLE_ADMIN');
	}
	
	public function getSalt()
	{
		// you *may* need a real salt depending on your encoder
		// see section on salt below
		return null;
	}
}
