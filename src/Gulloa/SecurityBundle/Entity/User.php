<?php

namespace Gulloa\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Gulloa\SecurityBundle\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{/**
 * @ORM\Id
 * @ORM\Column(type="integer")
 * @ORM\GeneratedValue(strategy="AUTO")
 */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "La clave debe ser de al menos {{ limit }} caracteres",
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password_token;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToMany(targetEntity="Role",inversedBy="users")
     * @ORM\JoinTable(name="user_role")
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity="\Cai\WebBundle\Entity\Categoria", inversedBy="users")
     * @ORM\JoinTable(name="user_web_categoria")
     */
    private $categorias;

    /**
     * @ORM\OneToMany(targetEntity="\Cai\WebBundle\Entity\Entrada", mappedBy="user")
     */
    private $entradas;

    /**
     * @ORM\OneToMany(targetEntity="\Cai\WebBundle\Entity\Pagina", mappedBy="user")
     */
    private $paginas;

    /**
     * @ORM\OneToOne(targetEntity="\Cai\WebBundle\Entity\UserProfile",mappedBy="user")
     */
    private $profile;

    /**
     * @ORM\OneToMany(targetEntity="\Cai\WebBundle\Entity\Seguimiento",mappedBy="user")
     */
    private $seguimientos;

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
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
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
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }



    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            ) = unserialize($serialized);
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categorias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->entradas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->paginas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seguimientos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add roles
     *
     * @param \Gulloa\SecurityBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\Gulloa\SecurityBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Gulloa\SecurityBundle\Entity\Role $roles
     */
    public function removeRole(\Gulloa\SecurityBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Add categorias
     *
     * @param \Cai\WebBundle\Entity\Categoria $categorias
     * @return User
     */
    public function addCategoria(\Cai\WebBundle\Entity\Categoria $categorias)
    {
        $this->categorias[] = $categorias;

        return $this;
    }

    /**
     * Remove categorias
     *
     * @param \Cai\WebBundle\Entity\Categoria $categorias
     */
    public function removeCategoria(\Cai\WebBundle\Entity\Categoria $categorias)
    {
        $this->categorias->removeElement($categorias);
    }

    /**
     * Get categorias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategorias()
    {
        return $this->categorias;
    }

    /**
     * Add entradas
     *
     * @param \Cai\WebBundle\Entity\Entrada $entradas
     * @return User
     */
    public function addEntrada(\Cai\WebBundle\Entity\Entrada $entradas)
    {
        $this->entradas[] = $entradas;

        return $this;
    }

    /**
     * Remove entradas
     *
     * @param \Cai\WebBundle\Entity\Entrada $entradas
     */
    public function removeEntrada(\Cai\WebBundle\Entity\Entrada $entradas)
    {
        $this->entradas->removeElement($entradas);
    }

    /**
     * Get entradas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntradas()
    {
        return $this->entradas;
    }

    /**
     * Add paginas
     *
     * @param \Cai\WebBundle\Entity\Pagina $paginas
     * @return User
     */
    public function addPagina(\Cai\WebBundle\Entity\Pagina $paginas)
    {
        $this->paginas[] = $paginas;

        return $this;
    }

    /**
     * Remove paginas
     *
     * @param \Cai\WebBundle\Entity\Pagina $paginas
     */
    public function removePagina(\Cai\WebBundle\Entity\Pagina $paginas)
    {
        $this->paginas->removeElement($paginas);
    }

    /**
     * Get paginas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPaginas()
    {
        return $this->paginas;
    }

    /*
     * toString
     * TODO: cambiar a nombre cuando haya UserExtend
     */
    public function __toString(){
        $profile = $this->profile;
        return $profile->getNombre() . ' ' . $profile->getApellido();
    }

    /**
     * Set profile
     *
     * @param \Cai\WebBundle\Entity\UserProfile $profile
     * @return User
     */
    public function setProfile(\Cai\WebBundle\Entity\UserProfile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Cai\WebBundle\Entity\UserProfile
     */
    public function getProfile()
    {
        return $this->profile;
    }


    /**
     * Set token
     *
     * @param string $token
     *
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set activationToken
     *
     * @param string $activationToken
     *
     * @return User
     */
    public function setActivationToken($activationToken)
    {
        $this->activation_token = $activationToken;

        return $this;
    }

    /**
     * Get activationToken
     *
     * @return string
     */
    public function getActivationToken()
    {
        return $this->activation_token;
    }

    /**
     * Set active
     *
     * @param  $active
     *
     * @return User
     */
    public function setActive( $active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set passwordToken
     *
     * @param string $passwordToken
     *
     * @return User
     */
    public function setPasswordToken($passwordToken)
    {
        $this->password_token = $passwordToken;

        return $this;
    }

    /**
     * Get passwordToken
     *
     * @return string
     */
    public function getPasswordToken()
    {
        return $this->password_token;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return $this->active;
    }

    /**
     * Add seguimiento
     *
     * @param \Cai\WebBundle\Entity\Seguimiento $seguimiento
     *
     * @return User
     */
    public function addSeguimiento(\Cai\WebBundle\Entity\Seguimiento $seguimiento)
    {
        $this->seguimientos[] = $seguimiento;

        return $this;
    }

    /**
     * Remove seguimiento
     *
     * @param \Cai\WebBundle\Entity\Seguimiento $seguimiento
     */
    public function removeSeguimiento(\Cai\WebBundle\Entity\Seguimiento $seguimiento)
    {
        $this->seguimientos->removeElement($seguimiento);
    }

    /**
     * Get seguimientos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeguimientos()
    {
        return $this->seguimientos;
    }
}
