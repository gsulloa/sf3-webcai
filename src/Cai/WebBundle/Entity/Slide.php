<?php

namespace Cai\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slide
 *
 * @ORM\Table(name="web_slide")
 * @ORM\Entity(repositoryClass="Cai\WebBundle\Repository\SlideRepository")
 */
class Slide
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
     * @var int
     *
     * @ORM\Column(name="posicion", type="integer")
     */
    private $posicion;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;


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
     * Set posicion
     *
     * @param integer $posicion
     *
     * @return Slide
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;

        return $this;
    }

    /**
     * Get posicion
     *
     * @return int
     */
    public function getPosicion()
    {
        return $this->posicion;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Slide
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}

