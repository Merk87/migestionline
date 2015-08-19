<?php

namespace MG\RepoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Repo
 *
 * @ORM\Table(name="repo")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MG\RepoBundle\Entity\RepoRepository")
 */
class Repo
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
     * @Assert\NotBlank(message="La descripción no puede estar vacía")
     * @ORM\Column(name="descripcion", type="string")
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="empresa_id", type="integer")
     */
    private $empresaId;

    /**
     *
     * @ORM\ManyToOne(targetEntity="MG\AdminBundle\Entity\Empresa", inversedBy="repo")
     * @ORM\JoinColumn(name="empresa_id", referencedColumnName="id")
     */
    private $empresa;

    /**
     * @ORM\OneToMany(targetEntity="MG\RepoBundle\Entity\Archivo", mappedBy="repo")
     */
    private $archivos;

    /**
     * @var integer
     *
     * @ORM\Column(name="limite_archivos", type="integer")
     */
    private $limiteArchivos;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     *
     */
    private $enabled;

    /**
     * @ORM\ManyToMany(targetEntity="MG\UserBundle\Entity\User", inversedBy="repo_users")
     * @ORM\JoinTable(name="repo_users")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="MG\UserBundle\Entity\User", inversedBy="repo_client")
     * @ORM\JoinTable(name="repo_clientes")
     */
    private $clientes;

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
     * Set limiteArchivos
     *
     * @param integer $limiteArchivos
     * @return Repo
     */
    public function setLimiteArchivos($limiteArchivos)
    {
        $this->limiteArchivos = $limiteArchivos;

        return $this;
    }

    /**
     * Get limiteArchivos
     *
     * @return integer 
     */
    public function getLimiteArchivos()
    {
        return $this->limiteArchivos;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Repo
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
     * Set empresaId
     *
     * @param integer $empresaId
     * @return Repo
     */
    public function setEmpresaId($empresaId)
    {
        $this->empresaId = $empresaId;

        return $this;
    }

    /**
     * Get empresaId
     *
     * @return integer 
     */
    public function getEmpresaId()
    {
        return $this->empresaId;
    }

    /**
     * Set empresa
     *
     * @param \MG\AdminBundle\Entity\Empresa $empresa
     * @return Repo
     */
    public function setEmpresa(\MG\AdminBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \MG\AdminBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Repo
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
     * Constructor
     */
    public function __construct()
    {
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add archivos
     *
     * @param \MG\RepoBundle\Entity\Archivo $archivos
     * @return Repo
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

    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Add users
     *
     * @param \MG\UserBundle\Entity\User $users
     * @return Repo
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

    /**
     * Add clientes
     *
     * @param \MG\UserBundle\Entity\User $clientes
     * @return Repo
     */
    public function addCliente(\MG\UserBundle\Entity\User $clientes)
    {
        if($clientes->getRolId() == 4)
        {
            $this->clientes[] = $clientes;
            return $this;
        }

        return null;

    }

    /**
     * Remove clientes
     *
     * @param \MG\UserBundle\Entity\User $clientes
     */
    public function removeCliente(\MG\UserBundle\Entity\User $clientes)
    {
        $this->clientes->removeElement($clientes);
    }

    /**
     * Get clientes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClientes()
    {
        return $this->clientes;
    }

}
