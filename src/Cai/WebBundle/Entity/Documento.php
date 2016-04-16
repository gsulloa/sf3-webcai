<?php

namespace Cai\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Documento
 *
 * @ORM\Table(name="web_documento")
 * @ORM\Entity(repositoryClass="Cai\WebBundle\Repository\DocumentoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Documento
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
     * @ORM\Column(name="extension", type="string", length=255, nullable=false)
     */
    protected $extension = 'pdf';

    /**
     *
     * @Assert\File(
     *  maxSize="15360k"
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
            : $this->getUploadRootDir() . $this->filename . '.' . $this->extension;
    }

    public function getWebPath()
    {
        return null === $this->filenamebinary
            ? null
            : $this->getUploadDir() . $this->filename . '.' . $this->extension;
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
        return 'uploads/biblioteca/documentos/' . $this->filenamebinary . '/';
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
            $this->extension = $extension;

            // do whatever you want to generate a unique name
            
            $this->filenamebinary = date_format(new \DateTime('now'),"Y/m/d");
            global $kernel;
            if ('AppCache' == get_class($kernel)) {
                $kernel = $kernel->getKernel();
            }
            $auxiliar = $kernel->getContainer()->get('cai_web.auxiliar');
            if ($this->filename != null) {
                $this->filename = $auxiliar->toAscii($this->filename);
            }else {
                $this->filename = $auxiliar->toAscii(str_replace('.' . $this->extension, '', $this->getFile()->getClientOriginalName()));
            }
            $this->filename = $auxiliar->slugGenerator($this->filename,$auxiliar->documentosGet($this));
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

        $this->getFile()->move($this->getUploadRootDir(), $this->filename . '.' . $this->extension);

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
     * @return Documento
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
     * @return Documento
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


    public function __toString()
    {
        return $this->filename . '.' . $this->extension;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return Documento
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }
}
