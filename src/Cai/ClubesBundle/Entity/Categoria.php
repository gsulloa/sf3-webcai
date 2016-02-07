<?php

namespace Cai\ClubesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categoria
 *
 * @ORM\Table(name="clubes_categoria")
 * @ORM\Entity(repositoryClass="Cai\ClubesBundle\Repository\CategoriaRepository")
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
     * @ORM\Column(name="etiqueta", type="string", length=255, unique=true)
     */
    private $etiqueta;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="\Cai\ClubesBundle\Entity\Club", mappedBy="categoria")
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
        $this->clubes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add clube
     *
     * @param \Cai\ClubesBundle\Entity\Club $clube
     *
     * @return Categoria
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
