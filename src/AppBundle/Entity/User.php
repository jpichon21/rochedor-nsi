<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity("username", message="validation.username.already_used", groups={"create"})
 */
class User implements UserInterface, \Serializable, \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=25, unique=true)
     * @Expose
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, unique=true)
     * @Expose
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=60)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=60, nullable=true)
     * @Expose
     */
    private $name;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="active", type="boolean")
     * @Expose
     */
    private $active;

    /**
     * @var json
     *
     * @ORM\Column(name="roles", type="json")
     * @Type("array")
     * @Expose
     */
    private $roles;

    /**
     * @var string|null
     *
     * @ORM\Column(name="resetToken", type="string", length=50, nullable=true)
     */
    private $resetToken;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="resetTokenExpiresAt", type="datetime", length=255, nullable=true)
     */
    private $resetTokenExpiresAt;

    public function __construct()
    {
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return User
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set active.
     *
     * @param bool|null $active
     *
     * @return User
     */
    public function setActive($active = null)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool|null
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set roles.
     *
     * @param json $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles.
     *
     * @return json
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set resetToken.
     *
     * @param string|null $resetToken
     *
     * @return User
     */
    public function setResetToken($resetToken = null)
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * Get resetToken.
     *
     * @return string|null
     */
    public function getResetToken()
    {
        return $this->resetToken;
    }

    /**
     * Set resetTokenExpiresAt.
     *
     * @param string|null $resetTokenExpiresAt
     *
     * @return User
     */
    public function setResetTokenExpiresAt($resetTokenExpiresAt = null)
    {
        $this->resetTokenExpiresAt = $resetTokenExpiresAt;

        return $this;
    }

    /**
     * Get resetTokenExpiresAt.
     *
     * @return \DateTime|null
     */
    public function getResetTokenExpiresAt()
    {
        return $this->resetTokenExpiresAt;
    }

    public function eraseCredentials()
    {
    }
    public function getSalt()
    {
        return null;
    }
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function jsonSerialize()
    {
        return array(
            "id"       => $this->id,
            "active"  => $this->active,
            "name" => $this->name,
            "email" => $this->email,
            "username" => $this->username,
            "username" => $this->username,
            "roles" => $this->roles
        );
    }

    /**
     * Check if the user has a role
     *
     * @param string $role
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }
}
