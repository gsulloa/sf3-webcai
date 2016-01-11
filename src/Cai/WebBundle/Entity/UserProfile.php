<?php

namespace Cai\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * UserProfile
 *
 * @ORM\Table(name="user_profile")
 * @ORM\Entity(repositoryClass="Cai\WebBundle\Repository\UserProfileRepository")
 * @ORM\HasLifecycleCallbacks
 */
class UserProfile
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
     * @ORM\Column(name="apellido", type="string", length=255)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="celular", type="string", length=255)
     */
    private $celular;

    /**
     * @var string
     *
     * @ORM\Column(name="n_identificador", type="string", length=255, unique=true)
     */
    private $nIdentificador;

    /**
     *
     * @Assert\File(
     *  maxSize="15360k",
     * mimeTypes = {"image/png","image/jpeg","image/gif"}
     * )
     * @Assert\NotBlank()
     */
    protected $file;
    protected $temp;
    /**
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=false)
     */
    protected $filename;



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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return UserProfile
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
     * Set apellido
     *
     * @param string $apellido
     *
     * @return UserProfile
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return UserProfile
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
     * Set celular
     *
     * @param string $celular
     *
     * @return UserProfile
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Get celular
     *
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set nIdentificador
     *
     * @param string $nIdentificador
     *
     * @return UserProfile
     */
    public function setNIdentificador($nIdentificador)
    {
        $this->nIdentificador = $nIdentificador;

        return $this;
    }

    /**
     * Get nIdentificador
     *
     * @return string
     */
    public function getNIdentificador()
    {
        return $this->nIdentificador;
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
        return 'uploads/biblioteca/profile/';
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
            $filename = sha1(uniqid(mt_rand(), true));
            $this->filename = $this->nombre . '.' . $extension; //$this->user->getUsername() $extension;
            //$this->filename = $this->getFile()->getClientOriginalName();
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

        $imgEditor->smart_resize_image($imagen, null, 200, 200, true, $imagen, true, false, 100);

        /*
        if (!is_dir($this->getUploadRootDir().'/small/')) {
            mkdir($this->getUploadRootDir().'/small/', 0777, true);
        }*/
        ## FIN CONFIGURACION #############################


        // check if we have an old image
        /*
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/big'.$this->temp);
            unlink($this->getUploadRootDir().'/small'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }*/
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