<?php

namespace MG\RepoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estado
 *
 * @ORM\Table(name="estado")
 * @ORM\Entity
 */
class Estado
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
     * @var string
     *
     * @ORM\Column(name="estado_code", type="string", length=255)
     */
    protected $estadoCode;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected  $enabled;

    /**
     * @ORM\OneToMany(targetEntity="Gestion", mappedBy="estado")
     */
    protected $gestiones;

    /**
     * @ORM\OneToMany(targetEntity="MG\MensajeriaBundle\Entity\Notificaciones", mappedBy="estado")
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
     * Set nombre
     *
     * @param string $nombre
     * @return Estado
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
     * @return Estado
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return Estado
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
     * Constructor
     */
    public function __construct()
    {
        $this->gestiones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add gestiones
     *
     * @param \MG\RepoBundle\Entity\Gestion $gestiones
     * @return Estado
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
     * Set estadoCode
     *
     * @param string $estadoCode
     * @return Estado
     */
    public function setEstadoCode($estadoCode)
    {
        $this->estadoCode = $estadoCode;

        return $this;
    }

    /**
     * Get estadoCode
     *
     * @return string 
     */
    public function getEstadoCode()
    {
        return $this->estadoCode;
    }

    /**
     * Add notificacion
     *
     * @param \MG\MensajeriaBundle\Entity\Notificaciones $notificacion
     * @return Estado
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
}
