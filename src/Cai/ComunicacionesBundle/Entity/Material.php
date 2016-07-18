<?php

namespace Cai\ComunicacionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Material
 *
 * @ORM\Table(name="com_material")
 * @ORM\Entity(repositoryClass="Cai\ComunicacionesBundle\Repository\MaterialRepository")
 */
class Material
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
     * @ORM\ManyToMany(targetEntity="Solicitud", mappedBy="materiales")
     */
    private $solicitudes;
    
    public function __toString()
    {
        return $this->etiqueta;
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
     * Set etiqueta
     *
     * @param string $etiqueta
     *
     * @return Material
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
     * Constructor
     */
    public function __construct()
    {
        $this->solicitudes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add solicitude
     *
     * @param \Cai\ComunicacionesBundle\Entity\Solicitud $solicitude
     *
     * @return Material
     */
    public function addSolicitude(\Cai\ComunicacionesBundle\Entity\Solicitud $solicitude)
    {
        $this->solicitudes[] = $solicitude;

        return $this;
    }

    /**
     * Remove solicitude
     *
     * @param \Cai\ComunicacionesBundle\Entity\Solicitud $solicitude
     */
    public function removeSolicitude(\Cai\ComunicacionesBundle\Entity\Solicitud $solicitude)
    {
        $this->solicitudes->removeElement($solicitude);
    }

    /**
     * Get solicitudes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSolicitudes()
    {
        return $this->solicitudes;
    }
}
