<?php

namespace MG\RepoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MG\AdminBundle\Entity\Empresa;
use MG\UserBundle\Entity\User;

/**
 * ClientContratacion
 *
 * @ORM\Table(name="client_contratacion")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MG\RepoBundle\Entity\ClientContratacionRepository")
 */
class ClientContratacion
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_solicitud", type="datetime")
     */
    private $fechaSolicitud;

    /**
     * @var integer
     *
     * @ORM\Column(name="cliente_id", type="integer")
     */
    private $clienteId;

    /**
    * @ORM\ManyToOne(targetEntity="MG\UserBundle\Entity\User", inversedBy="contratacionCliente")
     * @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
    */
    private $cliente;

    /**
     * @ORM\ManyToMany(targetEntity="ServType", inversedBy="contratacion")
     * @ORM\JoinTable(name="contratacion_servicios")
     */
    private $serviciosSolicitados;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vista", type="boolean")
     */
    private $vista;

    /**
     * @var boolean
     *
     * @ORM\Column(name="gestionada", type="boolean")
     */
    private $gestionada;

    /**
     * @var string
     *
     * @ORM\Column(name="servicios_personalizados", type="text", nullable=true)
     */
    private $serviciosPersonalizados;

    /**
     *
     * @ORM\ManyToMany(targetEntity="MG\RepoBundle\Entity\Servicios", inversedBy="solicitudesContratacion")
     * @ORM\JoinTable(name="servicios_seleccionados_empresa")
     */
    protected $serviciosSeleccionadosEmpresa;

  

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->serviciosSolicitados = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosSeleccionadosEmpresa = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set fechaSolicitud
     *
     * @param \DateTime $fechaSolicitud
     * @return ClientContratacion
     */
    public function setFechaSolicitud($fechaSolicitud)
    {
        $this->fechaSolicitud = $fechaSolicitud;

        return $this;
    }

    /**
     * Get fechaSolicitud
     *
     * @return \DateTime 
     */
    public function getFechaSolicitud()
    {
        return $this->fechaSolicitud;
    }

    /**
     * Set clienteId
     *
     * @param integer $clienteId
     * @return ClientContratacion
     */
    public function setClienteId($clienteId)
    {
        $this->clienteId = $clienteId;

        return $this;
    }

    /**
     * Get clienteId
     *
     * @return integer 
     */
    public function getClienteId()
    {
        return $this->clienteId;
    }

    /**
     * Set vista
     *
     * @param boolean $vista
     * @return ClientContratacion
     */
    public function setVista($vista)
    {
        $this->vista = $vista;

        return $this;
    }

    /**
     * Get vista
     *
     * @return boolean 
     */
    public function getVista()
    {
        return $this->vista;
    }

    /**
     * Set gestionada
     *
     * @param boolean $gestionada
     * @return ClientContratacion
     */
    public function setGestionada($gestionada)
    {
        $this->gestionada = $gestionada;

        return $this;
    }

    /**
     * Get gestionada
     *
     * @return boolean 
     */
    public function getGestionada()
    {
        return $this->gestionada;
    }

    /**
     * Set serviciosPersonalizados
     *
     * @param string $serviciosPersonalizados
     * @return ClientContratacion
     */
    public function setServiciosPersonalizados($serviciosPersonalizados)
    {
        $this->serviciosPersonalizados = $serviciosPersonalizados;

        return $this;
    }

    /**
     * Get serviciosPersonalizados
     *
     * @return string 
     */
    public function getServiciosPersonalizados()
    {
        return $this->serviciosPersonalizados;
    }

    /**
     * Set cliente
     *
     * @param User $cliente
     * @return ClientContratacion
     */
    public function setCliente(User $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \MG\UserBundle\Entity\User 
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Add serviciosSolicitados
     *
     * @param ServType $serviciosSolicitados
     * @return ClientContratacion
     */
    public function addServiciosSolicitado(ServType $serviciosSolicitados)
    {
        $this->serviciosSolicitados[] = $serviciosSolicitados;

        return $this;
    }

    /**
     * Remove serviciosSolicitados
     *
     * @param ServType $serviciosSolicitados
     */
    public function removeServiciosSolicitado(ServType $serviciosSolicitados)
    {
        $this->serviciosSolicitados->removeElement($serviciosSolicitados);
    }

    /**
     * Get serviciosSolicitados
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServiciosSolicitados()
    {
        return $this->serviciosSolicitados;
    }

    /**
     * Add serviciosSeleccionadosEmpresa
     *
     * @param Servicios $serviciosSeleccionadosEmpresa
     * @return ClientContratacion
     */
    public function addServiciosSeleccionadosEmpresa(Servicios $serviciosSeleccionadosEmpresa)
    {
        $this->serviciosSeleccionadosEmpresa[] = $serviciosSeleccionadosEmpresa;

        return $this;
    }

    /**
     * Remove serviciosSeleccionadosEmpresa
     *
     * @param Servicios $serviciosSeleccionadosEmpresa
     */
    public function removeServiciosSeleccionadosEmpresa(Servicios $serviciosSeleccionadosEmpresa)
    {
        $this->serviciosSeleccionadosEmpresa->removeElement($serviciosSeleccionadosEmpresa);
    }

    /**
     * Get serviciosSeleccionadosEmpresa
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServiciosSeleccionadosEmpresa()
    {
        return $this->serviciosSeleccionadosEmpresa;
    }
}
