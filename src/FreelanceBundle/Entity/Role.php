<?php

namespace FreelanceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="roles")
 */
class Role implements RoleInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->getName();
    }

    /**
     * @param EntityManager $em
     * @return Role
     */
    public static function getUserRole(EntityManager $em) {
        return $em->getRepository('FreelanceBundle:Role')->find(1);
    }

    /**
     * @param EntityManager $em
     * @return Role
     */
    public static function getAdminRole(EntityManager $em) {
        return $em->getRepository('FreelanceBundle:Role')->find(2);
    }

    /**
     * @param EntityManager $em
     * @return Role
     */
    public static function getSuperAdminRole(EntityManager $em) {
        return $em->getRepository('FreelanceBundle:Role')->find(3);
    }

    /**
     * @param EntityManager $em
     * @return Role
     */
    public static function getClientRole(EntityManager $em) {
        return $em->getRepository('FreelanceBundle:Role')->find(4);
    }

    /**
     * @param EntityManager $em
     * @return Role
     */
    public static function getFreelancerRole(EntityManager $em) {
        return $em->getRepository('FreelanceBundle:Role')->find(5);
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
