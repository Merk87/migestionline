<?php

namespace MG\LandingFrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contacto
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MG\LandingFrontBundle\Entity\ContactRepository"))
 */
class Contact
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
     * @ORM\Column(name="Nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="Asunto", type="string", length=255)
     */
    private $asunto;

    /**
     * @var string
     *
     * @ORM\Column(name="Mensaje", type="text", nullable=false)
     */
    private $mensaje;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Politica", type="boolean")
     */
    private $politica;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Publicidad", type="boolean", nullable=true)
     */
    private $publicidad;

    /**
     * @var boolean
     *
     * @ORM\Column(name="leido", type="boolean", nullable=false)
     */
    private $leido;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fechaEnvio;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_conversacion_publica", type="integer", nullable=false)
     */
    protected $idConversacionPublica;

    /**
     * @ORM\ManyToOne(targetEntity="ConversacionPublica", inversedBy="mensajesCli")
     * @ORM\JoinColumn(name="id_conversacion_publica", referencedColumnName="id")
     */
    protected $conversacionPublica;

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
     * @return Contacto
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
     * Set email
     *
     * @param string $email
     * @return Contacto
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
     * Set asunto
     *
     * @param string $asunto
     * @return Contacto
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

    /**
     * Set mensaje
     *
     * @param string $mensaje
     * @return Contacto
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
     * Set politica
     *
     * @param boolean $politica
     * @return Contacto
     */
    public function setPolitica($politica)
    {
        $this->politica = $politica;

        return $this;
    }

    /**
     * Get politica
     *
     * @return boolean 
     */
    public function getPolitica()
    {
        return $this->politica;
    }

    /**
     * Set publicidad
     *
     * @param boolean $publicidad
     * @return Contacto
     */
    public function setPublicidad($publicidad)
    {
        $this->publicidad = $publicidad;

        return $this;
    }

    /**
     * Get publicidad
     *
     * @return boolean 
     */
    public function getPublicidad()
    {
        return $this->publicidad;
    }

    /**
     * Set leido
     *
     * @param boolean $leido
     * @return Contact
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
     * Set idConversacionPublica
     *
     * @param integer $idConversacionPublica
     * @return Contact
     */
    public function setIdConversacionPublica($idConversacionPublica)
    {
        $this->idConversacionPublica = $idConversacionPublica;

        return $this;
    }

    /**
     * Get idConversacionPublica
     *
     * @return integer 
     */
    public function getIdConversacionPublica()
    {
        return $this->idConversacionPublica;
    }

    /**
     * Set conversacionPublica
     *
     * @param \MG\LandingFrontBundle\Entity\ConversacionPublica $conversacionPublica
     * @return Contact
     */
    public function setConversacionPublica(\MG\LandingFrontBundle\Entity\ConversacionPublica $conversacionPublica = null)
    {
        $this->conversacionPublica = $conversacionPublica;

        return $this;
    }

    /**
     * Get conversacionPublica
     *
     * @return \MG\LandingFrontBundle\Entity\ConversacionPublica 
     */
    public function getConversacionPublica()
    {
        return $this->conversacionPublica;
    }

    /**
     * Set fechaEnvio
     *
     * @param \DateTime $fechaEnvio
     * @return Contact
     */
    public function setFechaEnvio($fechaEnvio)
    {
        $this->fechaEnvio = $fechaEnvio;

        return $this;
    }

    /**
     * Get fechaEnvio
     *
     * @return \DateTime 
     */
    public function getFechaEnvio()
    {
        return $this->fechaEnvio;
    }
}
