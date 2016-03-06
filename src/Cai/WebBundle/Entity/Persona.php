<?php

namespace Cai\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Persona
 *
 * @ORM\Table(name="web_persona")
 * @ORM\Entity(repositoryClass="Cai\WebBundle\Repository\PersonaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Persona
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;
    /**
     * @var string
     *
     * @ORM\Column(name="categoria", type="string",length=255)
     */
    private $categoria;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion_corta", type="string", length=255, nullable=true)
     */
    private $descripcion_corta;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true, nullable=true)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    private $facebook;
    /**
     *
     * @Assert\File(
     *  maxSize="15360k",
     * mimeTypes = {"image/png","image/jpeg","image/gif"}
     * )
     */
    protected $file;
    protected $temp;
    /**
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    protected $filename;

    

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Persona
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set categoria
     *
     * @param string $categoria
     *
     * @return Persona
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return string
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set descripcionCorta
     *
     * @param string $descripcionCorta
     *
     * @return Persona
     */
    public function setDescripcionCorta($descripcionCorta)
    {
        $this->descripcion_corta = $descripcionCorta;

        return $this;
    }

    /**
     * Get descripcionCorta
     *
     * @return string
     */
    public function getDescripcionCorta()
    {
        return $this->descripcion_corta;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Persona
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Persona
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     *
     * @return Persona
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     *
     * @return Persona
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }




    /////////////////////////////////
    //Upload
    ////////////////////////////////

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->filename)) {
            // store the old name to delete after the update
            $this->temp = $this->filename;
            $this->filename = null;
        } else {
            $this->filename = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->filename
            ? null
            : $this->getUploadRootDir() . $this->filename;
    }

    public function getWebPath()
    {
        return null === $this->filename
            ? null
            : $this->getUploadDir();
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/biblioteca/persona/';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // compute a random name and try to guess the extension (more secure)
            $extension = $this->getFile()->guessExtension();
            if (!$extension) {
                // extension cannot be guessed
                $extension = 'bin';
            }
            // do whatever you want to generate a unique name
            global $kernel;
            if ('AppCache' == get_class($kernel)) {
                $kernel = $kernel->getKernel();
            }
            $auxiliar = $kernel->getContainer()->get('cai_web.auxiliar');
            $this->filename = $auxiliar->toAscii($this->categoria) . '-' . $auxiliar->toAscii($this->nombre) . '.' . $extension;
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $this->getFile()->move($this->getUploadRootDir(), $this->filename);

        ## CONFIGURACION #############################

        # ruta de la imagen a redimensionar
        $imagen = $this->getUploadRootDir() . $this->filename;

        global $kernel;
        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }

        $imgEditor = $kernel->getContainer()->get('cai_web.images');

        $imgEditor->smart_resize_image($imagen, null, 500, 500, true, $imagen, true, false, 100);

        ## FIN CONFIGURACION #############################

        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }


    /**
     * Set filename
     *
     * @param string $filename
     * @return UserProfile
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
}
