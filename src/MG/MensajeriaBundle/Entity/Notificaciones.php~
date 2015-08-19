<?php

namespace MG\MensajeriaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notificaciones
 *
 * @ORM\Table(name="notificaciones")
 * @ORM\Entity
 */
class Notificaciones
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
     * @var boolean
     *
     * @ORM\Column(name="active_for_user", type="boolean")
     */
    protected $activeForUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_destinatario", type="integer", nullable=false)
     */
    protected $idDestinatario;

    /**
     * @ORM\ManyToOne(targetEntity="MG\UserBundle\Entity\User", inversedBy="notificacionAsReciever")
     * @ORM\JoinColumn(name="id_destinatario", referencedColumnName="id")
     */
    protected $destinatarioCliente;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active_for_emp", type="boolean", options={"default" = true})
     */
    protected $activeForEmp;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_tipo", type="integer", nullable=false)
     */
    protected $idTipo;

    /**
     * @ORM\ManyToOne(targetEntity="TipoNotificacion", inversedBy="notificacion")
     * @ORM\JoinColumn(name="id_tipo", referencedColumnName="id")
     */
    protected $tipo;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_gestion", type="integer")
     */
    protected $idGestion;

    /**
     * @ORM\OneToOne(targetEntity="MG\RepoBundle\Entity\Gestion", inversedBy="notificacion")
     * @ORM\JoinColumn(name="id_gestion", referencedColumnName="id")
     */
    protected $gestion;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_estado", type="integer", nullable=false)
     */
    protected $idEstado;

    /**
     * @ORM\ManyToOne(targetEntity="MG\RepoBundle\Entity\Estado", inversedBy="notificacion")
     * @ORM\JoinColumn(name="id_estado", referencedColumnName="id")
     */
    protected $estado;



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
     * Set idTipo
     *
     * @param integer $idTipo
     * @return Notificaciones
     */
    public function setIdTipo($idTipo)
    {
        $this->idTipo = $idTipo;

        return $this;
    }

    /**
     * Get idTipo
     *
     * @return integer 
     */
    public function getIdTipo()
    {
        return $this->idTipo;
    }

    /**
     * Set idGestion
     *
     * @param integer $idGestion
     * @return Notificaciones
     */
    public function setIdGestion($idGestion)
    {
        $this->idGestion = $idGestion;

        return $this;
    }

    /**
     * Get idGestion
     *
     * @return integer 
     */
    public function getIdGestion()
    {
        return $this->idGestion;
    }

    /**
     * Set idEstado
     *
     * @param integer $idEstado
     * @return Notificaciones
     */
    public function setIdEstado($idEstado)
    {
        $this->idEstado = $idEstado;

        return $this;
    }

    /**
     * Get idEstado
     *
     * @return integer 
     */
    public function getIdEstado()
    {
        return $this->idEstado;
    }

    /**
     * Set tipo
     *
     * @param \MG\MensajeriaBundle\Entity\TipoNotificacion $tipo
     * @return Notificaciones
     */
    public function setTipo(\MG\MensajeriaBundle\Entity\TipoNotificacion $tipo = null)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return \MG\MensajeriaBundle\Entity\TipoNotificacion 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set gestion
     *
     * @param \MG\RepoBundle\Entity\Gestion $gestion
     * @return Notificaciones
     */
    public function setGestion(\MG\RepoBundle\Entity\Gestion $gestion = null)
    {
        $this->gestion = $gestion;

        return $this;
    }

    /**
     * Get gestion
     *
     * @return \MG\RepoBundle\Entity\Gestion 
     */
    public function getGestion()
    {
        return $this->gestion;
    }

    /**
     * Set estado
     *
     * @param \MG\RepoBundle\Entity\Estado $estado
     * @return Notificaciones
     */
    public function setEstado(\MG\RepoBundle\Entity\Estado $estado = null)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return \MG\RepoBundle\Entity\Estado 
     */
    public function getEstado()
    {
        return $this->estado;
    }

     /**
     * Set idDestinatario
     *
     * @param integer $idDestinatario
     * @return Notificaciones
     */
    public function setIdDestinatario($idDestinatario)
    {
        $this->idDestinatario = $idDestinatario;

        return $this;
    }

    /**
     * Get idDestinatario
     *
     * @return integer 
     */
    public function getIdDestinatario()
    {
        return $this->idDestinatario;
    }


    /**
     * Set destinatarioCliente
     *
     * @param \MG\UserBundle\Entity\User $destinatarioCliente
     * @return Notificaciones
     */
    public function setDestinatarioCliente(\MG\UserBundle\Entity\User $destinatarioCliente = null)
    {
        $this->destinatarioCliente = $destinatarioCliente;

        return $this;
    }

    /**
     * Get destinatarioCliente
     *
     * @return \MG\UserBundle\Entity\User 
     */
    public function getDestinatarioCliente()
    {
        return $this->destinatarioCliente;
    }

    /**
     * Set activeForEmp
     *
     * @param boolean $activeForEmp
     * @return Notificaciones
     */
    public function setActiveForEmp($activeForEmp)
    {
        $this->activeForEmp = $activeForEmp;

        return $this;
    }

    /**
     * Get activeForEmp
     *
     * @return boolean 
     */
    public function getActiveForEmp()
    {
        return $this->activeForEmp;
    }

    /**
     * Set activeForUser
     *
     * @param boolean $activeForUser
     * @return Notificaciones
     */
    public function setActiveForUser($activeForUser)
    {
        $this->activeForUser = $activeForUser;

        return $this;
    }

    /**
     * Get activeForUser
     *
     * @return boolean 
     */
    public function getActiveForUser()
    {
        return $this->activeForUser;
    }
}
