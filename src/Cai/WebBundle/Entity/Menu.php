<?php

namespace Cai\WebBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="web_menu")
 * @ORM\Entity(repositoryClass="Cai\WebBundle\Repository\MenuRepository")
 */
class Menu
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
     * @ORM\OneToMany(targetEntity="Elemento", mappedBy="menu")
     */
    private $elemento;

    /**
     * @ORM\OneToOne(targetEntity="Elemento", mappedBy="submenu")
     */
    private $submenu;


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
     * @return Menu
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
     * Constructor
     */
    public function __construct()
    {
        $this->elemento = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add elemento
     *
     * @param \Cai\WebBundle\Entity\Elemento $elemento
     *
     * @return Menu
     */
    public function addElemento(\Cai\WebBundle\Entity\Elemento $elemento)
    {
        $this->elemento[] = $elemento;

        return $this;
    }

    /**
     * Remove elemento
     *
     * @param \Cai\WebBundle\Entity\Elemento $elemento
     */
    public function removeElemento(\Cai\WebBundle\Entity\Elemento $elemento)
    {
        $this->elemento->removeElement($elemento);
    }

    /**
     * Get elemento
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElemento()
    {
        $sorting =$this->elemento->toArray();
        usort($sorting, function($a, $b)
        {
            if($a->getOrden() == $b->getOrden())return 0;
            else if($a->getOrden()  > $b->getOrden())return 1;
            else return -1;
        }
        );
        return new ArrayCollection($sorting);
    }




    /**
     * Set submenu
     *
     * @param \Cai\WebBundle\Entity\Elemento $submenu
     *
     * @return Menu
     */
    public function setSubmenu(\Cai\WebBundle\Entity\Elemento $submenu = null)
    {
        $this->submenu = $submenu;

        return $this;
    }

    /**
     * Get submenu
     *
     * @return \Cai\WebBundle\Entity\Elemento
     */
    public function getSubmenu()
    {
        return $this->submenu;
    }

    public function __toString()
    {
        return $this->titulo;
    }
}
