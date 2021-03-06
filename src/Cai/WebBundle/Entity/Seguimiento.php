<?php

namespace Cai\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seguimiento
 *
 * @ORM\Table(name="web_seguimiento")
 * @ORM\Entity(repositoryClass="Cai\WebBundle\Repository\SeguimientoRepository")
 */
class Seguimiento
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
     * @var integer
     *
     * @ORM\Column(name="etiqueta_id", type="integer", nullable=true)
     */
    private $etiqueta_id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var string
     * @ORM\Column(name="text", type="string", length=255, nullable=true)
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="\Gulloa\SecurityBundle\Entity\User", inversedBy="seguimientos")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


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
     * @return Seguimiento
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Seguimiento
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
     * Set user
     *
     * @param \Gulloa\SecurityBundle\Entity\User $user
     *
     * @return Seguimiento
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
     * Set etiquetaId
     *
     * @param integer $etiquetaId
     *
     * @return Seguimiento
     */
    public function setEtiquetaId($etiquetaId)
    {
        $this->etiqueta_id = $etiquetaId;

        return $this;
    }

    /**
     * Get etiquetaId
     *
     * @return integer
     */
    public function getEtiquetaId()
    {
        return $this->etiqueta_id;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Seguimiento
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}
