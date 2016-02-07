<?php

namespace Cai\ClubesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etiqueta
 *
 * @ORM\Table(name="clubes_etiqueta")
 * @ORM\Entity(repositoryClass="Cai\ClubesBundle\Repository\EtiquetaRepository")
 */
class Etiqueta
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
     * @ORM\Column(name="titulo", type="string", length=255, unique=true)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="\Cai\ClubesBundle\Entity\Club",mappedBy="etiquetas")
     */
    private $clubes;


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
     * @return Etiqueta
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
     * @return Etiqueta
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
        $this->clubes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add clube
     *
     * @param \Cai\ClubesBundle\Entity\Club $clube
     *
     * @return Etiqueta
     */
    public function addClube(\Cai\ClubesBundle\Entity\Club $clube)
    {
        $this->clubes[] = $clube;

        return $this;
    }

    /**
     * Remove clube
     *
     * @param \Cai\ClubesBundle\Entity\Club $clube
     */
    public function removeClube(\Cai\ClubesBundle\Entity\Club $clube)
    {
        $this->clubes->removeElement($clube);
    }

    /**
     * Get clubes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClubes()
    {
        return $this->clubes;
    }
}
