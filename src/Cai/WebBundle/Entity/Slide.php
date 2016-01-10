<?php

namespace Cai\WebBundle\Entity;

/**
 * Slide
 */
class Slide
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $posicion;

    /**
     * @var string
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

