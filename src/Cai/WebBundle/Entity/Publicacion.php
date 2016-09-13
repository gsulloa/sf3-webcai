<?php

namespace Cai\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Publicacion
 *
 * @ORM\Table(name="web_publicacion")
 * @ORM\Entity(repositoryClass="Cai\WebBundle\Repository\PublicacionRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "publicacion" = "Publicacion",
 *     "entrada" = "Entrada",
 *     "pagina"="Pagina",
 * })
 * @ORM\HasLifecycleCallbacks()
 */
class Publicacion
{
    protected $slugGenerator;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    protected $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="cuerpo", type="text")
     */
    protected $cuerpo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    protected $fecha;

    /**
     * @ORM\ManyToMany(targetEntity="Categoria", inversedBy="publicaciones")
     * @ORM\JoinTable(name="web_publicacion_categoria")
     */
    protected $categorias;

    /**
     * @ORM\ManyToOne(targetEntity="\Gulloa\SecurityBundle\Entity\User", inversedBy="publicaciones")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="publico", type="boolean")
     */
    protected $publico = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update_at", type="datetime")
     */
    protected $lastUpdateAt;

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
     * @return Publicacion
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
     * @return Publicacion
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
     * @return Publicacion
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
     * @return Publicacion
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
     * @return Publicacion
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
     * @return Publicacion
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
     * Set publico
     *
     * @param \bool $publico
     *
     * @return Publicacion
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

    public function setSlugGenerator($slugGenerator){
        $this->slugGenerator = $slugGenerator;
        return $this;
    }

    public function generateSlug(){
        $this->slug = $this->slugGenerator->generateSlug($this);
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
        $this->lastUpdateAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setLastUpdateAtValue()
    {
        $this->lastUpdateAt = new \DateTime();
    }

}
