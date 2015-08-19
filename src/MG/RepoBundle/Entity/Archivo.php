<?php

namespace MG\RepoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Archivo
 *
 * @ORM\Table(name="archivo")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MG\RepoBundle\Entity\ArchivoRepository")
 */
class Archivo
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
     * @var integer
     *
     * @ORM\Column(name="repo_id",type="integer")
     */
    private $repoId;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Repo", inversedBy="archivos")
     * @ORM\JoinColumn(name="repo_id", referencedColumnName="id")
     */
    private $repo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_subida", type="datetime")
     */
    private $fechaSubida;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_usuario", type="integer")
     */
    private $idUsuario;

    /**
     *
     * @ORM\ManyToOne(targetEntity="MG\UserBundle\Entity\User", inversedBy="archivos")
     * @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_gestion", type="integer")
     */
    private $idGestion;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Gestion", inversedBy="archivos")
     * @ORM\JoinColumn(name="id_gestion", referencedColumnName="id")
     */
    private $gestion;

    /**
     * @var array
     *
     * @Assert\File(maxSize="50M")
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="file_path", type="string", length=255)
     */
    private $filePath;

    /**
     * @var string
     *
     * @ORM\Column(name="original_name", type="string", length=255, nullable=false)
     */
    private $originalName;

    /**
     * @var string
     *
     * @ORM\Column(name="seg_name", type="string", length=255, nullable=false)
     */
    private $segName;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_filetype", type="integer")
     */
    private $idFiletype;

    /**
     *
     * @ORM\ManyToOne(targetEntity="FileTypes", inversedBy="archivos")
     * @ORM\JoinColumn(name="id_filetype", referencedColumnName="id")
     */
    private $fileType;

    /**
     * var boolean
     *
     * @ORM\Column(name="del_user", type="boolean", nullable=true)
     */
    private $delUser;


    /**
     * var boolean
     *
     * @ORM\Column(name="del_empresa", type="boolean", nullable=true)
     */
    private $delEmpresa;

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
     * Set idUsuario
     *
     * @param integer $idUsuario
     * @return Archivo
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return integer
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set file
     *
     * @param array $file
     * @return Archivo
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;

    }

    /**
     * Get file
     *
     * @return array
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set filePath
     *
     * @param string $filePath
     * @return Archivo
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set repo_id
     *
     * @param integer $repoId
     * @return Archivo
     */
    public function setRepoId($repoId)
    {
        $this->repoId = $repoId;

        return $this;
    }

    /**
     * Get repo_id
     *
     * @return integer
     */
    public function getRepoId()
    {
        return $this->repoId;
    }

    /**
     * Set repo
     *
     * @param \MG\RepoBundle\Entity\Repo $repo
     * @return Archivo
     */
    public function setRepo(\MG\RepoBundle\Entity\Repo $repo = null)
    {
        $this->repo = $repo;

        return $this;
    }

    /**
     * Get repo
     *
     * @return \MG\RepoBundle\Entity\Repo
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * Set user
     *
     * @param \MG\UserBundle\Entity\User $user
     * @return Archivo
     */
    public function setUser(\MG\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \MG\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set idGestion
     *
     * @param integer $idGestion
     * @return Archivo
     */
    public function setIdGestion($idGestion)
    {
        $this->idGestion = $idGestion;

        return $this;
    }

    /**
     * Get idGestion
     *
     * @return integer
     */
    public function getIdGestion()
    {
        return $this->idGestion;
    }

    /**
     * Set gestion
     *
     * @param \MG\RepoBundle\Entity\Gestion $gestion
     * @return Archivo
     */
    public function setGestion(\MG\RepoBundle\Entity\Gestion $gestion = null)
    {
        $this->gestion = $gestion;

        return $this;
    }

    /**
     * Get gestion
     *
     * @return \MG\RepoBundle\Entity\Gestion
     */
    public function getGestion()
    {
        return $this->gestion;
    }

    /**
     * Set originalName
     *
     * @param string $originalName
     * @return Archivo
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get originalName
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * Set segName
     *
     * @param string $segName
     * @return Archivo
     */
    public function setSegName($segName)
    {
        $this->segName = $segName;

        return $this;
    }

    /**
     * Get segName
     *
     * @return string
     */
    public function getSegName()
    {
        return $this->segName;
    }

    /**
     * Set idFiletype
     *
     * @param integer $idFiletype
     * @return Archivo
     */
    public function setIdFiletype($idFiletype)
    {
        $this->idFiletype = $idFiletype;

        return $this;
    }

    /**
     * Get idFiletype
     *
     * @return integer
     */
    public function getIdFiletype()
    {
        return $this->idFiletype;
    }

    /**
     * Set fileType
     *
     * @param \MG\RepoBundle\Entity\FileTypes $fileType
     * @return Archivo
     */
    public function setFileType(\MG\RepoBundle\Entity\FileTypes $fileType = null)
    {
        $this->fileType = $fileType;

        return $this;
    }

    /**
     * Get fileType
     *
     * @return \MG\RepoBundle\Entity\FileTypes
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    public function getUploadRootFileDir($empresa)
    {
        return __DIR__.'/../../../../web/'.$this->getUploadFileDir($empresa);
    }

    public function getUploadFileDir($emp)
    {

        return 'bundles/repobundle/repository/files/'.$emp.'/';
    }

    public function upload($empresa)
    {
        if(null === $this->getFile())
        {
            return;
        }

        $extension = $this->getFile()->getClientOriginalExtension();

        if($this->validExt($extension) == true)
        {
            $new_name =  uniqid().'.'.$extension;

            $this ->getFile()->move(
                $this->getUploadRootFileDir($empresa),
                $new_name
            );

            $original = $this->getFile()->getClientOriginalName();
            $this->setSegName($new_name);
            $this->setOriginalName($original);
            $this->filePath = $this->getUploadFileDir($empresa).$new_name;
            $ft = $this->checkFileType($extension);
            $this->setFechaSubida(new \DateTime());
            $this->setFileType($ft);
            $this->file = null;
            return true;;
        }else
        {
            return false;
        }
    }

    public function checkFileType($ext)
    {
        $find = false;
        global $kernel;
        if ( 'AppCache' == get_class($kernel) )
        {
            $kernel = $kernel->getKernel();
        }
        $em = $kernel->getContainer()->get( 'doctrine.orm.entity_manager' );

        $fileTypes = $em->getRepository('MGRepoBundle:FileTypes')
            ->findAll();

        $unkownType = $em->getRepository('MGRepoBundle:FileTypes')
            ->find(7);

        foreach($fileTypes as $ft)
        {
            if($ext == strtolower($ft->getTipo()))
            {
                $find = true;
                return $ft;
            }
        }

        if($find == false)
        {
            return $unkownType;
        }

        return 0;
    }

    public function validExt($ext)
    {
        if($ext == 'exe' || $ext == 'bat' || $ext == 'sql' || $ext == 'php' || $ext == 'js')
        {
            return false;
        }else
        {
            return true;
        }
    }

    public function servDownload()
    {

        $public = __DIR__.'/../../../../web/bundles/repobundle/repository/public/'.$this->getSegName();
        //$public = __DIR__.'/bundles/repobundle/repository/public/'.$this->getSegName();
        $origen = $this->getFilePath();
        $webpath = 'bundles/repobundle/repository/public/'.$this->getSegName();

        if(!copy($origen, $public))
        {
            return false;
        }else
        {

            return $webpath;
        }

    }

    /**
     * Set fechaSubida
     *
     * @param \DateTime $fechaSubida
     * @return Archivo
     */
    public function setFechaSubida($fechaSubida)
    {
        $this->fechaSubida = $fechaSubida;

        return $this;
    }

    /**
     * Get fechaSubida
     *
     * @return \DateTime
     */
    public function getFechaSubida()
    {
        return $this->fechaSubida;
    }

    /**
     * Set delUser
     *
     * @param boolean $delUser
     * @return Archivo
     */
    public function setDelUser($delUser)
    {
        $this->delUser = $delUser;

        return $this;
    }

    /**
     * Get delUser
     *
     * @return boolean
     */
    public function getDelUser()
    {
        return $this->delUser;
    }

    /**
     * Set delEmpresa
     *
     * @param boolean $delEmpresa
     * @return Archivo
     */
    public function setDelEmpresa($delEmpresa)
    {
        $this->delEmpresa = $delEmpresa;

        return $this;
    }

    /**
     * Get delEmpresa
     *
     * @return boolean
     */
    public function getDelEmpresa()
    {
        return $this->delEmpresa;
    }
}
