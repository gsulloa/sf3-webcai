<?php

namespace Cai\ReclamosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamo
 *
 * @ORM\Table(name="reclamos_reclamo")
 * @ORM\Entity(repositoryClass="Cai\ReclamosBundle\Repository\ReclamoRepository")
 */
class Reclamo
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
     * @ORM\Column(name="contacto", type="string", length=255)
     */
    private $contacto;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text")
     */
    private $texto;

    /**
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="reclamos")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id", nullable=false)
     */
    private $categoria;


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
     * Set contacto
     *
     * @param string $contacto
     *
     * @return Reclamo
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return string
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set texto
     *
     * @param string $texto
     *
     * @return Reclamo
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
     * Set categoria
     *
     * @param \Cai\ReclamosBundle\Entity\Categoria $categoria
     *
     * @return Reclamo
     */
    public function setCategoria(\Cai\ReclamosBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \Cai\ReclamosBundle\Entity\Categoria
     */
    public function getCategoria()
    {
        return $this->categoria;
    }
}