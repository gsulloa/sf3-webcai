<?php

namespace Cai\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Imagen
 *
 * @ORM\Table(name="web_imagen")
 * @ORM\Entity(repositoryClass="Cai\WebBundle\Repository\ImagenRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Imagen
{
    private $temp;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    protected $filename;

    /**
     *
     * @ORM\Column(name="filenamebinary", type="string", length=255, nullable=false)
     */
    protected $filenamebinary;

    /**
     *
     * @ORM\OneToMany(targetEntity="Entrada", mappedBy="imagen")
     */
    protected $entradas;

    /**
     * @ORM\OneToMany(targetEntity="Slide", mappedBy="imagen")
     */
    protected $slide;

    /**
     *
     * @Assert\File(
     *  maxSize="15360k",
     * mimeTypes = {"image/png","image/jpeg","image/gif"}
     * )
     * @Assert\NotBlank()
     */
    protected $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->filenamebinary)) {
            // store the old name to delete after the update
            $this->temp = $this->filenamebinary;
            $this->filenamebinary = null;
        } else {
            $this->filenamebinary = 'initial';
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
        return null === $this->filenamebinary
            ? null
            : $this->getUploadRootDir() . $this->filename;
    }

    public function getWebPath()
    {
        return null === $this->filenamebinary
            ? null
            : $this->getUploadDir() . '/' . $this->filenamebinary;
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
        return 'uploads/biblioteca/imagenes/' . $this->filenamebinary . '/';
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
            $this->filenamebinary = $filename;
            global $kernel;
            if ('AppCache' == get_class($kernel)) {
                $kernel = $kernel->getKernel();
            }
            $auxiliar = $kernel->getContainer()->get('cai_web.auxiliar');
            if ($this->filename != null) {
                $this->filename = $auxiliar->toAscii($this->filename) . '.' . $extension;
            }else {
                $this->filename = $auxiliar->toAscii($this->getFile()->getClientOriginalName()) . '.' . $extension;
            }
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
        //TEST


        # ruta de la imagen final, si se pone el mismo nombre que la imagen, esta se sobreescribe
        $imagen_final_small = $this->getUploadRootDir() . 'small-' . $this->filename;
        $imagen_final_auspicio = $this->getUploadRootDir() . 'auspicio-' . $this->filename;
        $imagen_final_slider = $this->getUploadRootDir() . 'slider-' . $this->filename;
        $imagen_final_noticia = $this->getUploadRootDir() . 'noticia-' . $this->filename;


        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }

        $imgEditor = $kernel->getContainer()->get('cai_web.images');


        $imgEditor->smart_resize_image($imagen,null,200,200,true,$imagen_final_small,false,false,50);
        $imgEditor->smart_resize_image($imagen,null,250,50,true,$imagen_final_auspicio,false,false,100);
        $imgEditor->smart_resize_image($imagen,null,1000,500,true,$imagen_final_slider,false,false,100);
        $imgEditor->smart_resize_image($imagen,null,150,150,true,$imagen_final_noticia,false,false,100);

        $size =getimagesize($this->getUploadRootDir() . $this->filename);
        if( $size[0] > 1500 || $size[1] > 1500){
            $imgEditor->smart_resize_image($imagen,null,1000,1000,true,$this->getUploadRootDir() . $this->filename,true,false,100);
        }

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
            unlink($this->getUploadRootDir() . '/small-' . $this->filename);
            unlink($this->getUploadRootDir() . '/medium-' . $this->filename);
            rmdir($this->getUploadDir());
        }
    }

    /**
     * Set filename
     *
     * @param string $filename
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

    public function getDeleteForm()
    {
        return $this->deleteForm;
    }

    public function setDeleteForm($deleteForm)
    {
        $this->deleteForm = $deleteForm;
    }

    public function __toString()
    {
        return $this->filename;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entradas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add entradas
     *
     * @param \Cai\WebBundle\Entity\Entrada $entradas
     * @return Imagen
     */
    public function addEntrada(\Cai\WebBundle\Entity\Entrada $entradas)
    {
        $this->entradas[] = $entradas;

        return $this;
    }

    /**
     * Remove entradas
     *
     * @param \Cai\WebBundle\Entity\Entrada $entradas
     */
    public function removeEntrada(\Cai\WebBundle\Entity\Entrada $entradas)
    {
        $this->entradas->removeElement($entradas);
    }

    /**
     * Get entradas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntradas()
    {
        return $this->entradas;
    }

    /**
     * Add slide
     *
     * @param \Cai\WebBundle\Entity\Slide $slide
     * @return Imagen
     */
    public function addSlide(\Cai\WebBundle\Entity\Slide $slide)
    {
        $this->slide[] = $slide;

        return $this;
    }

    /**
     * Remove slide
     *
     * @param \Cai\WebBundle\Entity\Slide $slide
     */
    public function removeSlide(\Cai\WebBundle\Entity\Slide $slide)
    {
        $this->slide->removeElement($slide);
    }

    /**
     * Get slide
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSlide()
    {
        return $this->slide;
    }
}
