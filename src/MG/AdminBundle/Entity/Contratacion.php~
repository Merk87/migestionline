<?php

namespace MG\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contratacion
 *
 * @ORM\Table(name="contratacion")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MG\AdminBundle\Entity\ContratacionRepository"))
 */
class Contratacion
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="nif", type="string", length=9, nullable=false)
     */
    private $NIF;

    /**
     * @var string
     *
     * @ORM\Column(name="domicilio_fiscal", type="string", length=255, nullable=true)
     */
    private $domicilioFiscal;

    /**
     * @var string
     *
     * @ORM\Column(name="domicilio_facturacion", type="string", length=255, nullable=false)
     */
    private $domicilioFacturacion;


    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=255, nullable=false)
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="pais", type="string", length=255, nullable=false)
     */
    private $pais;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_posta", type="string", length=10, nullable=false)
     */
    private $codigoPostal;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_mvl", type="string", length=255, nullable=true)
     */
    private $telefonoMvl;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     *
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="web", type="string", length=255, nullable=true)
     */
    private $web;

    /**
     * @var boolean
     *
     * @ORM\Column(name="aceptacion_pol", type="boolean", nullable=false)
     */
    private $aceptacionPol;


    /**
     * @var boolean
     *
     * @ORM\Column(name="aceptacion_contra", type="boolean", nullable=false)
     */
    private $aceptacionContra;

    /**
     * @var string
     *
     * @ORM\Column(name="cuenta_domiciliacion", type="string", length=20, nullable=false)
     */
    private $cuentaDomiciliacion;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="fecha_contratacion", type="date", nullable=false)
     */
    private $fechaContratacion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="readed", type="boolean", nullable=false)
     */
    private $readed;

    /**
* @var integer
*
* @ORM\Column(name="paquete_id", type="integer")
*/
    protected $idPaquete;

    /**
* @ORM\ManyToOne(targetEntity="Paquete", inversedBy="contratacion")
* @ORM\JoinColumn(name="paquete_id", referencedColumnName="id")
*/
    protected $paquete;

    /**
* @var integer
*
* @ORM\Column(name="periodo_id", type="integer")
*/
    protected $idPeriodo;

    /**
* @ORM\ManyToOne(targetEntity="PeriodosContratacion", inversedBy="contratacion")
* @ORM\JoinColumn(name="periodo_id", referencedColumnName="id")
*/
    protected $periodo;



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
     * Set name
     *
     * @param string $name
     * @return Contratacion
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set NIF
     *
     * @param string $nIF
     * @return Contratacion
     */
    public function setNIF($nIF)
    {
        $this->NIF = $nIF;

        return $this;
    }

    /**
     * Get NIF
     *
     * @return string 
     */
    public function getNIF()
    {
        return $this->NIF;
    }

    /**
     * Set domicilioFiscal
     *
     * @param string $domicilioFiscal
     * @return Contratacion
     */
    public function setDomicilioFiscal($domicilioFiscal)
    {
        $this->domicilioFiscal = $domicilioFiscal;

        return $this;
    }

    /**
     * Get domicilioFiscal
     *
     * @return string 
     */
    public function getDomicilioFiscal()
    {
        return $this->domicilioFiscal;
    }

    /**
     * Set domicilioFacturacion
     *
     * @param string $domicilioFacturacion
     * @return Contratacion
     */
    public function setDomicilioFacturacion($domicilioFacturacion)
    {
        $this->domicilioFacturacion = $domicilioFacturacion;

        return $this;
    }

    /**
     * Get domicilioFacturacion
     *
     * @return string 
     */
    public function getDomicilioFacturacion()
    {
        return $this->domicilioFacturacion;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     * @return Contratacion
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
     * @return Contratacion
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
     * Set codigoPostal
     *
     * @param string $codigoPostal
     * @return Contratacion
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
     * Set telefono
     *
     * @param string $telefono
     * @return Contratacion
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
     * Set telefonoMvl
     *
     * @param string $telefonoMvl
     * @return Contratacion
     */
    public function setTelefonoMvl($telefonoMvl)
    {
        $this->telefonoMvl = $telefonoMvl;

        return $this;
    }

    /**
     * Get telefonoMvl
     *
     * @return string 
     */
    public function getTelefonoMvl()
    {
        return $this->telefonoMvl;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Contratacion
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set web
     *
     * @param string $web
     * @return Contratacion
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

    /**
     * Set aceptacionPol
     *
     * @param boolean $aceptacionPol
     * @return Contratacion
     */
    public function setAceptacionPol($aceptacionPol)
    {
        $this->aceptacionPol = $aceptacionPol;

        return $this;
    }

    /**
     * Get aceptacionPol
     *
     * @return boolean 
     */
    public function getAceptacionPol()
    {
        return $this->aceptacionPol;
    }

    /**
     * Set aceptacionContra
     *
     * @param boolean $aceptacionContra
     * @return Contratacion
     */
    public function setAceptacionContra($aceptacionContra)
    {
        $this->aceptacionContra = $aceptacionContra;

        return $this;
    }

    /**
     * Get aceptacionContra
     *
     * @return boolean 
     */
    public function getAceptacionContra()
    {
        return $this->aceptacionContra;
    }

    /**
     * Set cuentaDomiciliacion
     *
     * @param string $cuentaDomiciliacion
     * @return Contratacion
     */
    public function setCuentaDomiciliacion($cuentaDomiciliacion)
    {
        $this->cuentaDomiciliacion = $cuentaDomiciliacion;

        return $this;
    }

    /**
     * Get cuentaDomiciliacion
     *
     * @return string 
     */
    public function getCuentaDomiciliacion()
    {
        return $this->cuentaDomiciliacion;
    }

    /**
     * Set readed
     *
     * @param boolean $readed
     * @return Contratacion
     */
    public function setReaded($readed)
    {
        $this->readed = $readed;

        return $this;
    }

    /**
     * Get readed
     *
     * @return boolean 
     */
    public function getReaded()
    {
        return $this->readed;
    }

    /**
     * Set idPaquete
     *
     * @param integer $idPaquete
     * @return Contratacion
     */
    public function setIdPaquete($idPaquete)
    {
        $this->idPaquete = $idPaquete;

        return $this;
    }

    /**
     * Get idPaquete
     *
     * @return integer 
     */
    public function getIdPaquete()
    {
        return $this->idPaquete;
    }

    /**
     * Set idPeriodo
     *
     * @param integer $idPeriodo
     * @return Contratacion
     */
    public function setIdPeriodo($idPeriodo)
    {
        $this->idPeriodo = $idPeriodo;

        return $this;
    }

    /**
     * Get idPeriodo
     *
     * @return integer 
     */
    public function getIdPeriodo()
    {
        return $this->idPeriodo;
    }

    /**
     * Set paquete
     *
     * @param \MG\AdminBundle\Entity\Paquete $paquete
     * @return Contratacion
     */
    public function setPaquete(\MG\AdminBundle\Entity\Paquete $paquete = null)
    {
        $this->paquete = $paquete;

        return $this;
    }

    /**
     * Get paquete
     *
     * @return \MG\AdminBundle\Entity\Paquete 
     */
    public function getPaquete()
    {
        return $this->paquete;
    }

    /**
     * Set periodo
     *
     * @param \MG\AdminBundle\Entity\PeriodosContratacion $periodo
     * @return Contratacion
     */
    public function setPeriodo(\MG\AdminBundle\Entity\PeriodosContratacion $periodo = null)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return \MG\AdminBundle\Entity\PeriodosContratacion 
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set fechaContratacion
     *
     * @param \DateTime $fechaContratacion
     * @return Contratacion
     */
    public function setFechaContratacion($fechaContratacion)
    {
        $this->fechaContratacion = $fechaContratacion;

        return $this;
    }

    /**
     * Get fechaContratacion
     *
     * @return \DateTime 
     */
    public function getFechaContratacion()
    {
        return $this->fechaContratacion;
    }
}
