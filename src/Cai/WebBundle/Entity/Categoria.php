<?php

namespace Cai\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categoria
 *
 * @ORM\Table(name="web_categoria")
 * @ORM\Entity(repositoryClass="Cai\WebBundle\Repository\CategoriaRepository")
 */
class Categoria
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="etiqueta", type="string", length=255)
     */
    private $etiqueta;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="\Gulloa\SecurityBundle\Entity\User", mappedBy="categorias")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="Publicacion", mappedBy="categorias")
     */
    private $publicaciones;


    /**
     * @ORM\OneToMany(targetEntity="Evento",mappedBy="categoria")
     */
    private $eventos;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set etiqueta
     *
     * @param string $etiqueta
     *
     * @return Categoria
     */
    public function setEtiqueta($etiqueta)
    {
        $this->etiqueta = $etiqueta;

        return $this;
    }

    /**
     * Get etiqueta
     *
     * @return string
     */
    public function getEtiqueta()
    {
        return $this->etiqueta;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Categoria
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->publicacions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->paginas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->eventos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function __toString()
    {
        return $this->etiqueta;
    }

    /**
     * Add user
     *
     * @param \Gulloa\SecurityBundle\Entity\User $user
     *
     * @return Categoria
     */
    public function addUser(\Gulloa\SecurityBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Gulloa\SecurityBundle\Entity\User $user
     */
    public function removeUser(\Gulloa\SecurityBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add publicacion
     *
     * @param \Cai\WebBundle\Entity\Entrada $publicacion
     *
     * @return Categoria
     */
    public function addPublicacion(\Cai\WebBundle\Entity\Publicacion $publicacion)
    {
        $this->publicaciones[] = $publicacion;

        return $this;
    }

    /**
     * Remove Publicacion
     *
     * @param \Cai\WebBundle\Entity\Publicacion $publicacion
     */
    public function removeEntrada(\Cai\WebBundle\Entity\Publicacion $publicacion)
    {
        $this->publicaciones->removeElement($publicacion);
    }

    /**
     * Get publicaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPublicaciones()
    {
        return $this->publicaciones;
    }

    /**
     * Add evento
     *
     * @param \Cai\WebBundle\Entity\Evento $evento
     *
     * @return Categoria
     */
    public function addEvento(\Cai\WebBundle\Entity\Evento $evento)
    {
        $this->eventos[] = $evento;

        return $this;
    }

    /**
     * Remove evento
     *
     * @param \Cai\WebBundle\Entity\Evento $evento
     */
    public function removeEvento(\Cai\WebBundle\Entity\Evento $evento)
    {
        $this->eventos->removeElement($evento);
    }

    /**
     * Get eventos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventos()
    {
        return $this->eventos;
    }
}
