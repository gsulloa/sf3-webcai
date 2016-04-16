<?php

namespace Cai\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entrada
 *
 * @ORM\Table(name="web_entrada")
 * @ORM\Entity(repositoryClass="Cai\WebBundle\Repository\EntradaRepository")
 */
class Entrada
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
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="cuerpo", type="text")
     */
    private $cuerpo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @ORM\ManyToMany(targetEntity="Categoria", inversedBy="entradas")
     * @ORM\JoinTable(name="web_entrada_categoria")
     */
    private $categorias;

    /**
     * @ORM\ManyToOne(targetEntity="\Gulloa\SecurityBundle\Entity\User", inversedBy="entradas")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Imagen",inversedBy="entradas")
     * @ORM\JoinColumn(name="imagen_id", referencedColumnName="id")
     */
    private $imagen;

    /**
     * @var boolean
     *
     * @ORM\Column(name="publico", type="boolean")
     */
    private $publico = false;

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
     * Set titulo
     *
     * @param string $titulo
     *
     * @return Entrada
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Entrada
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
     * Set cuerpo
     *
     * @param string $cuerpo
     *
     * @return Entrada
     */
    public function setCuerpo($cuerpo)
    {
        $this->cuerpo = $cuerpo;

        return $this;
    }

    /**
     * Get cuerpo
     *
     * @return string
     */
    public function getCuerpo()
    {
        return $this->cuerpo;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Entrada
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categorias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add categoria
     *
     * @param \Cai\WebBundle\Entity\Categoria $categoria
     *
     * @return Entrada
     */
    public function addCategoria(\Cai\WebBundle\Entity\Categoria $categoria)
    {
        $this->categorias[] = $categoria;

        return $this;
    }

    /**
     * Remove categoria
     *
     * @param \Cai\WebBundle\Entity\Categoria $categoria
     */
    public function removeCategoria(\Cai\WebBundle\Entity\Categoria $categoria)
    {
        $this->categorias->removeElement($categoria);
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
     * Set user
     *
     * @param \Gulloa\SecurityBundle\Entity\User $user
     *
     * @return Entrada
     */
    public function setUser(\Gulloa\SecurityBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Gulloa\SecurityBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set imagen
     *
     * @param \Cai\WebBundle\Entity\Imagen $imagen
     *
     * @return Entrada
     */
    public function setImagen(\Cai\WebBundle\Entity\Imagen $imagen = null)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return \Cai\WebBundle\Entity\Imagen
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set publico
     *
     * @param \bool $publico
     *
     * @return Entrada
     */
    public function setPublico($publico)
    {
        $this->publico = $publico;

        return $this;
    }

    /**
     * Get publico
     *
     * @return \bool
     */
    public function getPublico()
    {
        return $this->publico;
    }
}
