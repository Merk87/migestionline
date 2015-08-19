<?php

namespace MG\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Empresa
 *
 * @ORM\Table(name="empresa")
 * @ORM\Entity
 * @UniqueEntity(fields="CIF", message="El CIF ya existe en la base de datos")
 * @ORM\Entity(repositoryClass="MG\AdminBundle\Entity\EmpresaRepository"))
 */
class Empresa
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
     * @Assert\NotBlank(message="El CIF no puede estar vacio")
     * @ORM\Column(name="CIF", type="string", length=9, unique=true)
     */
    private $CIF;

    /**
     * @var string
     * @Assert\NotBlank(message="El nombre no puede estar vacio")
     * @ORM\Column(name="nombre", type="string", length=255, unique=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_postal  ", type="string", length=5)
     */
    private $codigoPostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=255)
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="pais", type="string", length=255)
     */
    private $pais;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=15)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="web", type="string", length=255)
     */
    private $web;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_path", type="string", length=255, nullable=true)
     */
    private $logoPath;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_title", type="string", length=255, nullable=true)
     */
    private $logoTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_alt", type="string", length=255, nullable=true)
     */
    private $logoAlt;

    /**
     * @Assert\File(maxSize="6000000")
     *
     */
    private $logoFile;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     *
     */
    private $enabled;

    /**
     * @var boolean
     *
     * @ORM\Column(name="public", type="boolean", nullable=false, options={"default" = true})
     */
    private $public;

    /**
     * @ORM\ManyToMany(targetEntity="MG\UserBundle\Entity\User", mappedBy="empresas")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="MG\RepoBundle\Entity\Repo", mappedBy="empresa")
     */
    private $repo;

    /**
     * @ORM\OneToMany(targetEntity="MG\RepoBundle\Entity\Servicios", mappedBy="empresa")
     */
    private $servicios;

    /**
     * @ORM\OneToMany(targetEntity="MG\RepoBundle\Entity\Gestion", mappedBy="empresa")
     */
    protected $gestiones;

     /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
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
* Set CIF
*
* @param string $CIF
* @return Empresa
*/
    public function setCIF($CIF)
    {
        $this->CIF = $CIF;

        return $this;
    }

    /**
* Get CIF
*
* @return string
*/
    public function getCIF()
    {
        return $this->CIF;
    }

    /**
* Set nombre
*
* @param string $nombre
* @return Empresa
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
* Set direccion
*
* @param string $direccion
* @return Empresa
*/
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
* Get direccion
*
* @return string
*/
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
* Set codigoPostal
*
* @param string $codigoPostal
* @return Empresa
*/
    public function setCodigoPostal($codigoPostal)
    {
        $this->codigoPostal = $codigoPostal;

        return $this;
    }

    /**
* Get codigoPostal
*
* @return string
*/
    public function getCodigoPostal()
    {
        return $this->codigoPostal;
    }

    /**
* Set ciudad
*
* @param string $ciudad
* @return Empresa
*/
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
* Get ciudad
*
* @return string
*/
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
* Set pais
*
* @param string $pais
* @return Empresa
*/
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
* Get pais
*
* @return string
*/
    public function getPais()
    {
        return $this->pais;
    }

    /**
* Set telefono
*
* @param string $telefono
* @return Empresa
*/
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
* Get telefono
*
* @return string
*/
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
* Set logoPath
*
* @param string $logoPath
* @return Empresa
*/
    public function setLogoPath($logoPath)
    {
        $this->logoPath = $logoPath;

        return $this;
    }

    /**
* Get logoPath
*
* @return string
*/
    public function getLogoPath()
    {
        return $this->logoPath;
    }

    /**
* Set logoTitle
*
* @param string $logoTitle
* @return Empresa
*/
    public function setLogoTitle($logoTitle)
    {
        $this->logoTitle = $logoTitle;

        return $this;
    }

    /**
* Get logoTitle
*
* @return string
*/
    public function getLogoTitle()
    {
        return $this->logoTitle;
    }

    /**
* Set logoAlt
*
* @param string $logoAlt
* @return Empresa
*/
    public function setLogoAlt($logoAlt)
    {
        $this->logoAlt = $logoAlt;

        return $this;
    }

    /**
* Get logoAlt
*
* @return string
*/
    public function getLogoAlt()
    {
        return $this->logoAlt;
    }

    /**
* Set enabled
*
* @param boolean $enabled
* @return Empresa
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
* set file
*
* @param UploadedFile $file
*/
    public function setLogoFile(UploadedFile $logoFile = null)
    {
        $this -> logoFile = $logoFile;
    }

    /**
* get file
*
* @param UploadedFile
*/
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /*********Paths, upload and more***********/

    public function getLogoAbsolutePath()
    {
        return null === $this -> logoPath
            ? null
            : $this->getUploadRootPicDir().'/'.$this->logoPath;
    }

    public function getLogoWebPath()
    {
        return null === $this->logoPath
            ? null
            : $this->getUploadPicDir();
    }

    public function getUploadRootPicDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadPicDir();
    }

    public function getUploadPicDir()
    {
        return 'bundles/adminbundle/empresas/img_logos/';
    }

    public function upload()
    {
        if(null === $this->getLogoFile())
        {
            return;
        }

        $extension = $this->getLogoFile()->getClientOriginalExtension();
        $new_name =  uniqid().'.'.$extension;
        $this -> getLogoFile()->move(
          $this->getUploadRootPicDir(),
          $new_name
        );

        $this->logoPath = $this->getUploadPicDir().$new_name;
        $this->logoFile = null;
    }


    /**
     * Add users
     *
     * @param \MG\UserBundle\Entity\User $users
     * @return Empresa
     */
    public function addUser(\MG\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;
        return $this;
    }

    /**
     * Remove users
     *
     * @param \MG\UserBundle\Entity\User $users
     */
    public function removeUser(\MG\UserBundle\Entity\User $users)
    {
       $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Add repo
     *
     * @param \MG\RepoBundle\Entity\Repo $repo
     * @return Empresa
     */
    public function addRepo(\MG\RepoBundle\Entity\Repo $repo)
    {
        $this->repo[] = $repo;

        return $this;
    }

    /**
     * Remove repo
     *
     * @param \MG\RepoBundle\Entity\Repo $repo
     */
    public function removeRepo(\MG\RepoBundle\Entity\Repo $repo)
    {
        $this->repo->removeElement($repo);
    }

    /**
     * Get repo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * Add servicios
     *
     * @param \MG\RepoBundle\Entity\Servicios $servicios
     * @return Empresa
     */
    public function addServicio(\MG\RepoBundle\Entity\Servicios $servicios)
    {
        $this->servicios[] = $servicios;

        return $this;
    }

    /**
     * Remove servicios
     *
     * @param \MG\RepoBundle\Entity\Servicios $servicios
     */
    public function removeServicio(\MG\RepoBundle\Entity\Servicios $servicios)
    {
        $this->servicios->removeElement($servicios);
    }

    /**
     * Get servicios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServicios()
    {
        return $this->servicios;
    }

    /**
     * Add gestiones
     *
     * @param \MG\RepoBundle\Entity\Gestion $gestiones
     * @return Empresa
     */
    public function addGestione(\MG\RepoBundle\Entity\Gestion $gestiones)
    {
        $this->gestiones[] = $gestiones;

        return $this;
    }

    /**
     * Remove gestiones
     *
     * @param \MG\RepoBundle\Entity\Gestion $gestiones
     */
    public function removeGestione(\MG\RepoBundle\Entity\Gestion $gestiones)
    {
        $this->gestiones->removeElement($gestiones);
    }

    /**
     * Get gestiones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGestiones()
    {
        return $this->gestiones;
    }

    /**
     * Set public
     *
     * @param boolean $public
     * @return Empresa
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return boolean 
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Get Users de empresa por su rol.
     * @param $rolName
     * @return array|bool
     */

    public function getUsersByRol($rolName)
    {
        $allu = $this->getUsers();

        if(sizeof($allu) > 0)
        {
            foreach($allu as $u)
            {
                if($u->getRol()->getRolName() == $rolName)
                {
                    $users[] = $u;
                }
            }
        }

       if(isset($users))
       {
           return $users;
       }else
       {
           return false;
       }

    }

    public function getEmployees()
    {
        $allu = $this->getUsers();
        if(sizeof($allu) > 0)
        {
            foreach($allu as $u)
            {
                if($u->getRol()->getRolName() == 'ROLE_ADMIN' || $u->getRol()->getRolName() == 'ROLE_SUBGES' )
                {
                    $users[] = $u;
                }
            }
        }

        if(isset($users))
        {
            return $users;
        }else
        {
            return false;
        }
    }

    public function getClients()
    {
        $allu = $this->getUsers();
        if(sizeof($allu) > 0)
        {
            foreach($allu as $u)
            {
                if($u->getRol()->getRolName() == 'ROLE_CLIENTE')
                {
                    $users[] = $u;
                }
            }
        }

        if(isset($users))
        {
            return $users;
        }else
        {
            $users = array();
            return $users;
        }
    }

    /**
     * Add solicitudesContratacion
     *
     * @param \MG\RepoBundle\Entity\ClientContratacion $solicitudesContratacion
     * @return Empresa
     */
    public function addSolicitudesContratacion(\MG\RepoBundle\Entity\ClientContratacion $solicitudesContratacion)
    {
        $this->solicitudesContratacion[] = $solicitudesContratacion;

        return $this;
    }

    /**
     * Remove solicitudesContratacion
     *
     * @param \MG\RepoBundle\Entity\ClientContratacion $solicitudesContratacion
     */
    public function removeSolicitudesContratacion(\MG\RepoBundle\Entity\ClientContratacion $solicitudesContratacion)
    {
        $this->solicitudesContratacion->removeElement($solicitudesContratacion);
    }

    /**
     * Get solicitudesContratacion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSolicitudesContratacion()
    {
        return $this->solicitudesContratacion;
    }

    /**
     * Set web
     *
     * @param string $web
     * @return Empresa
     */
    public function setWeb($web)
    {
        $this->web = $web;

        return $this;
    }

    /**
     * Get web
     *
     * @return string 
     */
    public function getWeb()
    {
        return $this->web;
    }
}
