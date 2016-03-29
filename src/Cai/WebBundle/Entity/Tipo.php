<?php

namespace Cai\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tipo
 *
 * @ORM\Table(name="web_tipo")
 * @ORM\Entity(repositoryClass="Cai\WebBundle\Repository\TipoRepository")
 */
class Tipo
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
     * @ORM\OneToMany(targetEntity="Pagina", mappedBy="tipo")
     */
    private $pagina;


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
     * @return Tipo
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
        $this->pagina = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pagina
     *
     * @param \Cai\WebBundle\Entity\Pagina $pagina
     *
     * @return Tipo
     */
    public function addPagina(\Cai\WebBundle\Entity\Pagina $pagina)
    {
        $this->pagina[] = $pagina;

        return $this;
    }

    /**
     * Remove pagina
     *
     * @param \Cai\WebBundle\Entity\Pagina $pagina
     */
    public function removePagina(\Cai\WebBundle\Entity\Pagina $pagina)
    {
        $this->pagina->removeElement($pagina);
    }

    /**
     * Get pagina
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagina()
    {
        return $this->pagina;
    }
}
