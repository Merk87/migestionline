<?php

namespace MG\MensajeriaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mensajes
 *
 * @ORM\Table(name="mensajes")
 * @ORM\Entity
 */
class Mensajes
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
     * @var integer
     *
     * @ORM\Column(name="id_remitente", type="integer")
     */
    protected $idRemitente;

    /**
     * @ORM\ManyToOne(targetEntity="MG\UserBundle\Entity\User", inversedBy="mensajesAsSender")
     * @ORM\JoinColumn(name="id_remitente", referencedColumnName="id")
     */
    protected $remitente;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_destinatario", type="integer")
     */
    protected $idDestinatario;

    /**
     * @ORM\ManyToOne(targetEntity="MG\UserBundle\Entity\User", inversedBy="mensajesAsReceiver")
     * @ORM\JoinColumn(name="id_destinatario", referencedColumnName="id")
     */
    protected $destinatario;

     /**
     * @var string
     *
     * @ORM\Column(name="mensaje", type="text")
     */
        protected $mensaje;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="datetime")
     */
    protected  $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_lectura", type="datetime", nullable=true)
     */
    protected  $fechaLectura;

    /**
     * var boolean
     *
     * @ORM\Column(name="leido", type="boolean")
     */
    protected $leido;

    /**
     * @var string
     *
     * @ORM\Column(name="asunto", type="string", nullable=true)
     * @ORM\Column(name="asunto", type="string", nullable=true)
     */
    protected $asunto;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_status", type="integer", nullable=false)
     */
    protected $idStatus;

    /**
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="mensaje")
     * @ORM\JoinColumn(name="id_status", referencedColumnName="id")
     */
    protected $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_conversacion", type="integer", nullable=false)
     */
    protected $idConversacion;

    /**
     * @ORM\ManyToOne(targetEntity="Conversaciones", inversedBy="mensajes")
     * @ORM\JoinColumn(name="id_conversacion", referencedColumnName="id")
     */
    protected $conversacion;


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
     * Set idRemitente
     *
     * @param integer $idRemitente
     * @return Mensajes
     */
    public function setIdRemitente($idRemitente)
    {
        $this->idRemitente = $idRemitente;

        return $this;
    }

    /**
     * Get idRemitente
     *
     * @return integer 
     */
    public function getIdRemitente()
    {
        return $this->idRemitente;
    }

    /**
     * Set remitente
     *
     * @param string $remitente
     * @return Mensajes
     */
    public function setRemitente($remitente)
    {
        $this->remitente = $remitente;

        return $this;
    }

    /**
     * Get remitente
     *
     * @return string 
     */
    public function getRemitente()
    {
        return $this->remitente;
    }

    /**
     * Set idDestinatario
     *
     * @param string $idDestinatario
     * @return Mensajes
     */
    public function setIdDestinatario($idDestinatario)
    {
        $this->idDestinatario = $idDestinatario;

        return $this;
    }

    /**
     * Get idDestinatario
     *
     * @return string 
     */
    public function getIdDestinatario()
    {
        return $this->idDestinatario;
    }

    /**
     * Set destinatario
     *
     * @param string $destinatario
     * @return Mensajes
     */
    public function setDestinatario($destinatario)
    {
        $this->destinatario = $destinatario;

        return $this;
    }

    /**
     * Get destinatario
     *
     * @return string 
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * Set mensaje
     *
     * @param string $mensaje
     * @return Mensajes
     */
    public function setMensaje($mensaje)
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    /**
     * Get mensaje
     *
     * @return string 
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Mensajes
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set idStatus
     *
     * @param string $idStatus
     * @return Mensajes
     */
    public function setIdStatus($idStatus)
    {
        $this->idStatus = $idStatus;

        return $this;
    }

    /**
     * Get idStatus
     *
     * @return string 
     */
    public function getIdStatus()
    {
        return $this->idStatus;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Mensajes
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaLectura
     *
     * @param \DateTime $fechaLectura
     * @return Mensajes
     */
    public function setFechaLectura($fechaLectura)
    {
        $this->fechaLectura = $fechaLectura;

        return $this;
    }

    /**
     * Get fechaLectura
     *
     * @return \DateTime 
     */
    public function getFechaLectura()
    {
        return $this->fechaLectura;
    }

    /**
     * Set leido
     *
     * @param boolean $leido
     * @return Mensajes
     */
    public function setLeido($leido)
    {
        $this->leido = $leido;

        return $this;
    }

    /**
     * Get leido
     *
     * @return boolean 
     */
    public function getLeido()
    {
        return $this->leido;
    }

 
    /**
     * Set idConversacion
     *
     * @param integer $idConversacion
     * @return Mensajes
     */
    public function setIdConversacion($idConversacion)
    {
        $this->idConversacion = $idConversacion;

        return $this;
    }

    /**
     * Get idConversacion
     *
     * @return integer 
     */
    public function getIdConversacion()
    {
        return $this->idConversacion;
    }

    /**
     * Set conversacion
     *
     * @param \MG\MensajeriaBundle\Entity\Conversaciones $conversacion
     * @return Mensajes
     */
    public function setConversacion(\MG\MensajeriaBundle\Entity\Conversaciones $conversacion = null)
    {
        $this->conversacion = $conversacion;

        return $this;
    }

    /**
     * Get conversacion
     *
     * @return \MG\MensajeriaBundle\Entity\Conversaciones 
     */
    public function getConversacion()
    {
        return $this->conversacion;
    }


    /**
     * Set asunto
     *
     * @param string $asunto
     * @return Mensajes
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

        return $this;
    }

    /**
     * Get asunto
     *
     * @return string 
     */
    public function getAsunto()
    {
        return $this->asunto;
    }
}
