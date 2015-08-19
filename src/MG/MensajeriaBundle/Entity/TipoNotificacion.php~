<?php

namespace MG\MensajeriaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoNotificacion
 *
 * @ORM\Table(name="tipo_notificacion")
 * @ORM\Entity
 */
class TipoNotificacion
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
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    protected $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_code", type="string", length=255)
     */
    protected $tipoCode;

    /**
     * @ORM\OneToMany(targetEntity="Notificaciones", mappedBy="tipo")
     */
    protected $notificacion;



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
     * Constructor
     */
    public function __construct()
    {
        $this->notificacion = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return TipoNotificacion
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Add notificacion
     *
     * @param \MG\MensajeriaBundle\Entity\Notificaciones $notificacion
     * @return TipoNotificacion
     */
    public function addNotificacion(\MG\MensajeriaBundle\Entity\Notificaciones $notificacion)
    {
        $this->notificacion[] = $notificacion;

        return $this;
    }

    /**
     * Remove notificacion
     *
     * @param \MG\MensajeriaBundle\Entity\Notificaciones $notificacion
     */
    public function removeNotificacion(\MG\MensajeriaBundle\Entity\Notificaciones $notificacion)
    {
        $this->notificacion->removeElement($notificacion);
    }

    /**
     * Get notificacion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotificacion()
    {
        return $this->notificacion;
    }

    /**
     * Set tipoCode
     *
     * @param string $tipoCode
     * @return TipoNotificacion
     */
    public function setTipoCode($tipoCode)
    {
        $this->tipoCode = $tipoCode;

        return $this;
    }

    /**
     * Get tipoCode
     *
     * @return string 
     */
    public function getTipoCode()
    {
        return $this->tipoCode;
    }
}
