<?php

namespace MG\RepoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * FileTypes
 *
 * @ORM\Table(name="filetypes")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MG\RepoBundle\Entity\FileTypesRepository")
 */
class FileTypes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255) *
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="icono_path", type="string", length=255)
     */
    private $iconoPath;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @Assert\File(maxSize="6000000")
     *
     */
    private $iconoFile;

    /**
     * @ORM\OneToMany(targetEntity="Archivo", mappedBy="fileType")
     */
    protected $archivos;

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
     * Constructor
     */
    public function __construct()
    {
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return FileTypes
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return FileTypes
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
     * Set iconoPath
     *
     * @param string $iconoPath
     * @return FileTypes
     */
    public function setIconoPath($iconoPath)
    {
        $this->iconoPath = $iconoPath;

        return $this;
    }

    /**
     * Get iconoPath
     *
     * @return string 
     */
    public function getIconoPath()
    {
        return $this->iconoPath;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return FileTypes
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Add archivos
     *
     * @param \MG\RepoBundle\Entity\Archivo $archivos
     * @return FileTypes
     */
    public function addArchivo(\MG\RepoBundle\Entity\Archivo $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \MG\RepoBundle\Entity\Archivo $archivos
     */
    public function removeArchivo(\MG\RepoBundle\Entity\Archivo $archivos)
    {
        $this->archivos->removeElement($archivos);
    }

    /**
     * Get archivos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivos()
    {
        return $this->archivos;
    }

    /**
     * set file
     *
     * @param UploadedFile $file
     */
    public function setIconoFile(UploadedFile $logoFile = null)
    {
        $this -> iconoFile = $logoFile;
    }

    /**
     * get file
     *
     * @param UploadedFile
     */
    public function getIconoFile()
    {
        return $this->iconoFile;
    }

    /*********Paths, upload and more***********/

    public function getIconoAbsolutePath()
    {
        return null === $this -> iconoPath
            ? null
            : $this->getUploadRootPicDir().'/'.$this->iconoPath;
    }

    public function getIconoWebPath()
    {
        return null === $this->iconoPath
            ? null
            : $this->getUploadPicDir();
    }

    public function getUploadRootPicDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadPicDir();
    }

    public function getUploadPicDir()
    {
        return 'bundles/adminbundle/img/icons/';
    }

    public function upload()
    {
        if(null === $this->getIconoFile())
        {
            return;
        }

        $this -> getIconoFile()->move(
            $this->getUploadRootPicDir(),
            $this->getIconoFile()->getClientOriginalName()
        );

        $this->iconoPath = $this->getUploadPicDir().$this->getIconoFile()->getClientOriginalName();
        $this->iconoFile = null;
    }



}
