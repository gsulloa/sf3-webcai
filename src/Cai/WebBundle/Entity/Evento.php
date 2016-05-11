<?php

namespace Cai\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evento
 *
 * @ORM\Table(name="web_evento")
 * @ORM\Entity(repositoryClass="Cai\WebBundle\Repository\EventoRepository")
 */
class Evento
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text")
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="datetime")
     */
    private $fecha_inicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_fin", type="datetime")
     */
    private $fecha_fin;

    /**
     * @var boolean
     * @ORM\Column(name="allDay", type="boolean")
     */
    private $allDay;

    /**
     * @var Categoria
     *
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="eventos")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    private $categoria;

    /**
     * @var Imagen
     *
     * @ORM\ManyToOne(targetEntity="Imagen", inversedBy="eventos")
     * @ORM\JoinColumn(name="imagen_id", referencedColumnName="id")
     */
    private $imagen;



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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Evento
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Evento
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
     * Set categoria
     *
     * @param \Cai\WebBundle\Entity\Categoria $categoria
     *
     * @return Evento
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return Evento
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fecha_inicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fecha_inicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     *
     * @return Evento
     */
    public function setFechaFin($fechaFin)
    {
        $this->fecha_fin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fecha_fin;
    }

    /**
     * Set allDay
     *
     * @param boolean $allDay
     *
     * @return Evento
     */
    public function setAllDay($allDay)
    {
        $this->allDay = $allDay;

        return $this;
    }

    /**
     * Get allDay
     *
     * @return boolean
     */
    public function getAllDay()
    {
        return $this->allDay;
    }

    /**
     * Set imagen
     *
     * @param \Cai\WebBundle\Entity\Imagen $imagen
     *
     * @return Evento
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
}
