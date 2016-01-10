<?php

namespace Cai\WebBundle\Entity;

/**
 * Imagen
 */
class Imagen
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $filenamebinary;


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
     * Set filename
     *
     * @param string $filename
     *
     * @return Imagen
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set filenamebinary
     *
     * @param string $filenamebinary
     *
     * @return Imagen
     */
    public function setFilenamebinary($filenamebinary)
    {
        $this->filenamebinary = $filenamebinary;

        return $this;
    }

    /**
     * Get filenamebinary
     *
     * @return string
     */
    public function getFilenamebinary()
    {
        return $this->filenamebinary;
    }
}

