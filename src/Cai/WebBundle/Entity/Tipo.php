<?php

namespace Cai\WebBundle\Entity;

/**
 * Tipo
 */
class Tipo
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $etiqueta;


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
}

