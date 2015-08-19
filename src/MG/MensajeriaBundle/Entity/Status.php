<?php

namespace MG\MensajeriaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Status
 *
 * @ORM\Table(name="status")
 * @ORM\Entity
 */
class Status
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
     *
     * @ORM\Column(name="status_code", type="string", length=255)
     */
    protected $statusCode;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    protected $descripcion;

    /**
     * @ORM\OneToMany(targetEntity="Mensajes", mappedBy="status")
     */
    protected $mensaje;

    /**
     * @ORM\OneToMany(targetEntity="MG\RepoBundle\Entity\Comentarios", mappedBy="status")
     */
    protected $comentario;



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
     * Set statusCode
     *
     * @param string $statusCode
     * @return Status
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get statusCode
     *
     * @return string 
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Status
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
        $this->mensaje = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add mensaje
     *
     * @param \MG\MensajeriaBundle\Entity\Mensajes $mensaje
     * @return Status
     */
    public function addMensaje(\MG\MensajeriaBundle\Entity\Mensajes $mensaje)
    {
        $this->mensaje[] = $mensaje;

        return $this;
    }

    /**
     * Remove mensaje
     *
     * @param \MG\MensajeriaBundle\Entity\Mensajes $mensaje
     */
    public function removeMensaje(\MG\MensajeriaBundle\Entity\Mensajes $mensaje)
    {
        $this->mensaje->removeElement($mensaje);
    }

    /**
     * Get mensaje
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMensaje()
    {
        return $this->mensaje;
    }

    /**
     * Add comentario
     *
     * @param \MG\MensajeriaBundle\Entity\Mensajes $comentario
     * @return Status
     */
    public function addComentario(\MG\MensajeriaBundle\Entity\Mensajes $comentario)
    {
        $this->comentario[] = $comentario;

        return $this;
    }

    /**
     * Remove comentario
     *
     * @param \MG\MensajeriaBundle\Entity\Mensajes $comentario
     */
    public function removeComentario(\MG\MensajeriaBundle\Entity\Mensajes $comentario)
    {
        $this->comentario->removeElement($comentario);
    }

    /**
     * Get comentario
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComentario()
    {
        return $this->comentario;
    }
}
