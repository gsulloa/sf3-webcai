<?php

namespace Cai\ComunicacionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Solicitud
 *
 * @ORM\Table(name="com_solicitud")
 * @ORM\Entity(repositoryClass="Cai\ComunicacionesBundle\Repository\SolicitudRepository")
 */
class Solicitud
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
     * @ORM\Column(name="tipo", type="integer")
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text")
     */
    private $texto;

    /**
     * @var string
     *
     * @ORM\Column(name="ideas", type="text", nullable=true)
     */
    private $ideas;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @ORM\ManyToMany(targetEntity="Material", inversedBy="solicitudes")
     * @ORM\JoinTable(name="solicitud_material")
     */
    private $materiales;


    /**
     * @ORM\ManyToOne(targetEntity="\Gulloa\SecurityBundle\Entity\User", inversedBy="solicitudes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="\Cai\WebBundle\Entity\Categoria", inversedBy="solicitudes")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    private $categoria;

    /**
     * @ORM\Column(name="revisada", type="boolean")
     */
    private $revisada = false;
    /**
     * @ORM\Column(name="aceptada", type="boolean", nullable=true)
     */
    private $aceptada;

    /**
     * @ORM\Column(name="completada", type="boolean")
     */
    private $completada = false;
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->titulo;
    }

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
     * @return Solicitud
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
     * Set texto
     *  
     * @param string $texto
     *
     * @return Solicitud
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Solicitud
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Solicitud
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set ideas
     *
     * @param string $ideas
     *
     * @return Solicitud
     */
    public function setIdeas($ideas)
    {
        $this->ideas = $ideas;

        return $this;
    }

    /**
     * Get ideas
     *
     * @return string
     */
    public function getIdeas()
    {
        return $this->ideas;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return Solicitud
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->materiales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fecha = new \DateTime();
    }

    /**
     * Add materiale
     *
     * @param \Cai\ComunicacionesBundle\Entity\Material $materiale
     *
     * @return Solicitud
     */
    public function addMateriale(\Cai\ComunicacionesBundle\Entity\Material $materiale)
    {
        $this->materiales[] = $materiale;

        return $this;
    }

    /**
     * Remove materiale
     *
     * @param \Cai\ComunicacionesBundle\Entity\Material $materiale
     */
    public function removeMateriale(\Cai\ComunicacionesBundle\Entity\Material $materiale)
    {
        $this->materiales->removeElement($materiale);
    }

    /**
     * Get materiales
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMateriales()
    {
        return $this->materiales;
    }

    /**
     * Set user
     *
     * @param \Gulloa\SecurityBundle\Entity\User $user
     *
     * @return Solicitud
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
     * Set categoria
     *
     * @param \Cai\WebBundle\Entity\Categoria $categoria
     *
     * @return Solicitud
     */
    public function setCategoria(\Cai\WebBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \Cai\WebBundle\Entity\Categoria
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set aceptada
     *
     * @param boolean $aceptada
     *
     * @return Solicitud
     */
    public function setAceptada($aceptada)
    {
        $this->aceptada = $aceptada;

        return $this;
    }

    /**
     * Get aceptada
     *
     * @return boolean
     */
    public function getAceptada()
    {
        return $this->aceptada;
    }

    /**
     * Set completada
     *
     * @param boolean $completada
     *
     * @return Solicitud
     */
    public function setCompletada($completada)
    {
        $this->completada = $completada;

        return $this;
    }

    /**
     * Get completada
     *
     * @return boolean
     */
    public function getCompletada()
    {
        return $this->completada;
    }
    
    public function aceptar(){
        $this->revisada = true;
        $this->aceptada = true;
        return $this;
    }
    
    public function rechazar(){
        $this->revisada = true;
        $this->aceptada = false;
        return $this;
    }
    
    public function completar(){
        $this->completada = true;
    }

    /**
     * Set revisada
     *
     * @param boolean $revisada
     *
     * @return Solicitud
     */
    public function setRevisada($revisada)
    {
        $this->revisada = $revisada;

        return $this;
    }

    /**
     * Get revisada
     *
     * @return boolean
     */
    public function getRevisada()
    {
        return $this->revisada;
    }

    /**
     * @return int
     * 0: no esta revisada
     * 1: rechazada
     * 2: aceptada pero por realizar
     * 3: completada
     */
    public function getEstado(){
        if(!$this->revisada){
            return 0;
        }else{
            if(!$this->aceptada){
                return 1;
            }else{
                if(!$this->completada){
                    return 2;
                }else{
                    return 3;
                }
            }
        }
    }
}
