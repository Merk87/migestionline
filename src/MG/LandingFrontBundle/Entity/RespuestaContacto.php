<?php

namespace MG\LandingFrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RespuestaContacto
 *
 * @ORM\Table(name="respuesta_contacto")
 * @ORM\Entity
 */
class RespuestaContacto
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
     * @ORM\Column(name="id_autor", type="integer")
     */
    private $idAutor;

    /**
     * @ORM\ManyToOne(targetEntity="MG\UserBundle\Entity\User", inversedBy="autorRespuestaContacto")
     * @ORM\JoinColumn(name="id_autor", referencedColumnName="id")
     */
    private $autor;

    /**
     * @var string
     *
     * @ORM\Column(name="mensaje", type="text")
     */
    private $mensaje;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_conversacion_publica", type="integer")
     */
    private $idConversacionPublica;

    /**
     * @ORM\ManyToOne(targetEntity="ConversacionPublica", inversedBy="mensajesRes")
     * @ORM\JoinColumn(name="id_conversacion_publica", referencedColumnName="id")
     */
    private $conversacionPublica;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_respuesta", type="date", nullable=false)
     */
    private $fechaRespuesta;


 

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
     * Set idAutor
     *
     * @param integer $idAutor
     * @return RespuestaContacto
     */
    public function setIdAutor($idAutor)
    {
        $this->idAutor = $idAutor;

        return $this;
    }

    /**
     * Get idAutor
     *
     * @return integer 
     */
    public function getIdAutor()
    {
        return $this->idAutor;
    }

    /**
     * Set mensaje
     *
     * @param string $mensaje
     * @return RespuestaContacto
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
     * Set idConversacionPublica
     *
     * @param integer $idConversacionPublica
     * @return RespuestaContacto
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
     * Set fechaRespuesta
     *
     * @param \DateTime $fechaRespuesta
     * @return RespuestaContacto
     */
    public function setFechaRespuesta($fechaRespuesta)
    {
        $this->fechaRespuesta = $fechaRespuesta;

        return $this;
    }

    /**
     * Get fechaRespuesta
     *
     * @return \DateTime 
     */
    public function getFechaRespuesta()
    {
        return $this->fechaRespuesta;
    }

    /**
     * Set autor
     *
     * @param \MG\UserBundle\Entity\User $autor
     * @return RespuestaContacto
     */
    public function setAutor(\MG\UserBundle\Entity\User $autor = null)
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * Get autor
     *
     * @return \MG\UserBundle\Entity\User 
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * Set conversacionPublica
     *
     * @param \MG\LandingFrontBundle\Entity\ConversacionPublica $conversacionPublica
     * @return RespuestaContacto
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
}
