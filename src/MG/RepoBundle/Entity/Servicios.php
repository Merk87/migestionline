<?php

namespace MG\RepoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Servicios
 *
 * @ORM\Table(name="servicios")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MG\RepoBundle\Entity\ServiciosRepository")
 */
class Servicios
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    protected $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    protected $descripcion;

    /**
     * @var integer
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="precio", type="integer", nullable=false)
     */
    protected $precio;

    /**
     * @var integer
     *
     * @ORM\Column(name="empresa_id", type="integer")
     */
    protected $empresaId;

   /**
    *
    * @ORM\ManyToOne(targetEntity="MG\AdminBundle\Entity\Empresa", inversedBy="servicios")
    * @ORM\JoinColumn(name="empresa_id", referencedColumnName="id")
    */
    protected $empresa;

    /**
     * @ORM\OneToMany(targetEntity="MG\RepoBundle\Entity\Categoria", mappedBy="servicio")
     */
    protected $categoria;

    /**
     * @ORM\ManyToMany(targetEntity="MG\UserBundle\Entity\User", inversedBy="serv_users")
     * @ORM\JoinTable(name="serv_users")
     */
    protected $users;

    /**
     * @ORM\ManyToMany(targetEntity="MG\UserBundle\Entity\User", inversedBy="serv_clientes")
     * @ORM\JoinTable(name="serv_clientes")
     */
    protected $clientes;

    /**
     * @var boolean
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    /**
     * @ORM\ManyToMany(targetEntity="MG\RepoBundle\Entity\ClientContratacion", mappedBy="serviciosSeleccionadosEmpresa")
     */
    private $solicitudesContratacion;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categoria = new ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Servicios
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Servicios
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
     * Set precio
     *
     * @param integer $precio
     * @return Servicios
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return integer 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set empresaId
     *
     * @param integer $empresaId
     * @return Servicios
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
     * @return Servicios
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
     * Add categoria
     *
     * @param \MG\RepoBundle\Entity\Categoria $categoria
     * @return Servicios
     */
    public function addCategorium(\MG\RepoBundle\Entity\Categoria $categoria)
    {
        $this->categoria[] = $categoria;

        return $this;
    }

    /**
     * Remove categoria
     *
     * @param \MG\RepoBundle\Entity\Categoria $categoria
     */
    public function removeCategorium(\MG\RepoBundle\Entity\Categoria $categoria)
    {
        $this->categoria->removeElement($categoria);
    }

    /**
     * Get categoria
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Add users
     *
     * @param \MG\UserBundle\Entity\User $users
     * @return Servicios
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return Servicios
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

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function hasCategories()
    {
        $cat = $this->getCategoria();
        if (isset($cat) && sizeof($cat) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    /**
     * Add clientes
     *
     * @param \MG\UserBundle\Entity\User $clientes
     * @return Servicios
     */
    public function addCliente(\MG\UserBundle\Entity\User $clientes)
    {
        $this->clientes[] = $clientes;

        return $this;
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

    /**
     * Add servMensaje
     *
     * @param \MG\MensajeriaBundle\Entity\Conversaciones $servMensaje
     * @return Servicios
     */
    public function addServMensaje(\MG\MensajeriaBundle\Entity\Conversaciones $servMensaje)
    {
        $this->servMensaje[] = $servMensaje;

        return $this;
    }

    /**
     * Remove servMensaje
     *
     * @param \MG\MensajeriaBundle\Entity\Conversaciones $servMensaje
     */
    public function removeServMensaje(\MG\MensajeriaBundle\Entity\Conversaciones $servMensaje)
    {
        $this->servMensaje->removeElement($servMensaje);
    }

    /**
     * Get servMensaje
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServMensaje()
    {
        return $this->servMensaje;
    }

    /**
     * Add servConv
     *
     * @param \MG\MensajeriaBundle\Entity\Conversaciones $servConv
     * @return Servicios
     */
    public function addServConv(\MG\MensajeriaBundle\Entity\Conversaciones $servConv)
    {
        $this->servConv[] = $servConv;

        return $this;
    }

    /**
     * Remove servConv
     *
     * @param \MG\MensajeriaBundle\Entity\Conversaciones $servConv
     */
    public function removeServConv(\MG\MensajeriaBundle\Entity\Conversaciones $servConv)
    {
        $this->servConv->removeElement($servConv);
    }

    /**
     * Get servConv
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServConv()
    {
        return $this->servConv;
    }

    /**
     * Add solicitudesContratacion
     *
     * @param \MG\RepoBundle\Entity\ClientContratacion $solicitudesContratacion
     * @return Servicios
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
}
