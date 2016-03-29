<?php

namespace Cai\ReclamosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categoria
 *
 * @ORM\Table(name="reclamos_categoria")
 * @ORM\Entity(repositoryClass="Cai\ReclamosBundle\Repository\CategoriaRepository")
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
     * @ORM\Column(name="Etiqueta", type="string", length=255)
     */
    private $etiqueta;

    /**
     * @ORM\OneToMany(targetEntity="Reclamo", mappedBy="categoria")
     */
    private $reclamos;


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

    public function __toString()
    {
        return $this->etiqueta;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reclamos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add reclamo
     *
     * @param \Cai\ReclamosBundle\Entity\Reclamo $reclamo
     *
     * @return Categoria
     */
    public function addReclamo(\Cai\ReclamosBundle\Entity\Reclamo $reclamo)
    {
        $this->reclamos[] = $reclamo;

        return $this;
    }

    /**
     * Remove reclamo
     *
     * @param \Cai\ReclamosBundle\Entity\Reclamo $reclamo
     */
    public function removeReclamo(\Cai\ReclamosBundle\Entity\Reclamo $reclamo)
    {
        $this->reclamos->removeElement($reclamo);
    }

    /**
     * Get reclamos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReclamos()
    {
        return $this->reclamos;
    }
}
