<?php

namespace FreelanceBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="FreelanceBundle\Entity\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements UserInterface, \Serializable {
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registered", type="datetime")
     */
    private $registered;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastactive", type="datetime")
     */
    private $lastactive;

    /**
     * @var boolean
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255)
     */
    private $avatar;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="user_role",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     *
     * @var ArrayCollection $roles
     */
    protected $roles;

    private $salt;

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     * @return ArrayCollection
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return '7v8b6ghjb6834bdkjndsjb233409fjvsiu8892d';
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {

    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->name,
            $this->surname,
            $this->registered,
            $this->lastactive,
            $this->active
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->email,
            $this->name,
            $this->surname,
            $this->registered,
            $this->lastactive,
            $this->active
        ) = unserialize($serialized);
    }

    /**
     * @param EntityManager $em
     * @param EncoderFactory $encoderFactory
     * @param $parameters
     */
    public static function addUser(EntityManager $em, $encoderFactory, $parameters)
    {
        $user = new User();
        $encoder = $encoderFactory->getEncoder($user);

        $user->setUsername($parameters['username']);
        $user->setPassword($encoder->encodePassword($parameters['password'], $user->getSalt()));
        $user->setEmail($parameters['email']);
        $user->setName($parameters['name']);
        $user->setSurname($parameters['surname']);
        $user->setRegistered(new \DateTime());
        $user->setLastactive(new \DateTime());
        $user->setActive(false);
        $user->setAvatar('files/default/default-avatar.png');

        $user->addRole(Role::getUserRole($em));
        if ($parameters['role'] === 'client') {
            $user->addRole(Role::getClientRole($em));
        } else if ($parameters['role'] === 'freelancer') {
            $user->addRole(Role::getFreelancerRole($em));
        }

        $em->persist($user);
        $em->flush();
    }

    /**
     * @param EntityManager $em
     * @param EncoderFactory $encoderFactory
     * @param User $user
     * @param $parameters
     * @return integer
     */
    public static function changePassword($em, $encoderFactory, User $user, $parameters)
    {
        $encoder = $encoderFactory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($parameters['currentPassword'], $user->getSalt());
        if ($encodedPassword != $user->getPassword())
            return 0;
        if ($parameters['newPassword'] != $parameters['repeatNewPassword'])
            return 1;
        $usr = $em->getRepository('FreelanceBundle:User')->find($user->getId());
        $newPassword = $encoder->encodePassword($parameters['newPassword'], $user->getSalt());
        $usr->setPassword($newPassword);
        $em->flush();
        return 2;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
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

    /**
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set registered
     *
     * @param \DateTime $registered
     * @return User
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;

        return $this;
    }

    /**
     * Get registered
     *
     * @return \DateTime 
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * Set lastactive
     *
     * @param \DateTime $lastactive
     * @return User
     */
    public function setLastactive($lastactive)
    {
        $this->lastactive = $lastactive;

        return $this;
    }

    /**
     * Get lastactive
     *
     * @return \DateTime 
     */
    public function getLastactive()
    {
        return $this->lastactive;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add roles
     *
     * @param \FreelanceBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\FreelanceBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \FreelanceBundle\Entity\Role $roles
     */
    public function removeRole(\FreelanceBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }
}
