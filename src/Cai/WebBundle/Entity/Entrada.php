<?php

namespace Cai\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entrada
 *
 * @ORM\Table(name="web_entrada")
 * @ORM\Entity(repositoryClass="Cai\WebBundle\Repository\EntradaRepository")
 */
class Entrada extends Publicacion
{
    /**
     * @ORM\ManyToOne(targetEntity="Imagen",inversedBy="entradas")
     * @ORM\JoinColumn(name="imagen_id", referencedColumnName="id")
     */
    private $imagen;

    /**
     * Set imagen
     *
     * @param \Cai\WebBundle\Entity\Imagen $imagen
     *
     * @return Entrada
     */
    public function setImagen(\Cai\WebBundle\Entity\Imagen $imagen = null)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return \Cai\WebBundle\Entity\Imagen
     */
    public function getImagen()
    {
        return $this->imagen;
    }
}
