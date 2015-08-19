<?php

namespace MG\LandingFrontBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ConversacionPublica
 *
 * @ORM\Table(name="conversacion_publica")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MG\LandingFrontBundle\Entity\ConversacionPublicaRepository"))
 */
class ConversacionPublica
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
     * @ORM\Column(name="fecha_creacion", type="date")
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_ultimo", type="date")
     */
    private $fechaUltimo;

    /**
     * @var string
     *
     * @ORM\Column(name="hash_conv", type="string", length=255, nullable=false)
     */
    private $hashConv;
    /**
     * @var boolean
     *
     * @ORM\Column(name="activa", type="boolean", nullable=false)
     */
    private $activa;

    /**
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="conversacionPublica")
     */
    private $mensajesCli;

    /**
     * @ORM\OneToMany(targetEntity="RespuestaContacto", mappedBy="conversacionPublica")
     */
    private $mensajesRes;



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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return ConversacionPublica
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
     * Set fechaUltimo
     *
     * @param \DateTime $fechaUltimo
     * @return ConversacionPublica
     */
    public function setFechaUltimo($fechaUltimo)
    {
        $this->fechaUltimo = $fechaUltimo;

        return $this;
    }

    /**
     * Get fechaUltimo
     *
     * @return \DateTime 
     */
    public function getFechaUltimo()
    {
        return $this->fechaUltimo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mensajesCli = new ArrayCollection();
        $this->mensajesRes = new ArrayCollection();
    }

    /**
     * Set hashConv
     *
     * @param string $hashConv
     * @return ConversacionPublica
     */
    public function setHashConv($hashConv)
    {
        $this->hashConv = $hashConv;

        return $this;
    }

    /**
     * Get hashConv
     *
     * @return string 
     */
    public function getHashConv()
    {
        return $this->hashConv;
    }

    /**
     * Add mensajesCli
     *
     * @param \MG\LandingFrontBundle\Entity\Contact $mensajesCli
     * @return ConversacionPublica
     */
    public function addMensajesCli(\MG\LandingFrontBundle\Entity\Contact $mensajesCli)
    {
        $this->mensajesCli[] = $mensajesCli;

        return $this;
    }

    /**
     * Remove mensajesCli
     *
     * @param \MG\LandingFrontBundle\Entity\Contact $mensajesCli
     */
    public function removeMensajesCli(\MG\LandingFrontBundle\Entity\Contact $mensajesCli)
    {
        $this->mensajesCli->removeElement($mensajesCli);
    }

    /**
     * Get mensajesCli
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMensajesCli()
    {
        return $this->mensajesCli;
    }

    /**
     * Add mensajesRes
     *
     * @param \MG\LandingFrontBundle\Entity\RespuestaContacto $mensajesRes
     * @return ConversacionPublica
     */
    public function addMensajesRe(\MG\LandingFrontBundle\Entity\RespuestaContacto $mensajesRes)
    {
        $this->mensajesRes[] = $mensajesRes;

        return $this;
    }

    /**
     * Remove mensajesRes
     *
     * @param \MG\LandingFrontBundle\Entity\RespuestaContacto $mensajesRes
     */
    public function removeMensajesRe(\MG\LandingFrontBundle\Entity\RespuestaContacto $mensajesRes)
    {
        $this->mensajesRes->removeElement($mensajesRes);
    }

    /**
     * Get mensajesRes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMensajesRes()
    {
        return $this->mensajesRes;
    }

    /**
     * Set activa
     *
     * @param boolean $activa
     * @return ConversacionPublica
     */
    public function setActiva($activa)
    {
        $this->activa = $activa;

        return $this;
    }

    /**
     * Get activa
     *
     * @return boolean 
     */
    public function getActiva()
    {
        return $this->activa;
    }

    public function hasUnread()
    {
        $mensajesPendientes = false;
        $mensajes = $this->getMensajesCli();

        foreach($mensajes as $m)
        {
            if($m->getLeido() == false)
            {
                $mensajesPendientes = true;
            }
        }
        return $mensajesPendientes;
    }

    public function getUnread()
    {
        $mensajes = $this->getMensajesCli();

        $noLeidos = array();

        foreach($mensajes as $m)
        {
            if($m->getLeido() == false)
            {
                $noLeidos[] = $m;
            }
        }

        if(sizeof($noLeidos) > 0)
        {
            return $noLeidos;
        }else
        {
            return 0;
        }
    }

    public function countUnread()
    {
        $countedMsjsPendientes = 0;
        $mensajes = $this->getMensajesCli();

        foreach($mensajes as $m)
        {
            if($m->getLeido() == false)
            {
                $countedMsjsPendientes++;
            }
        }
        return $countedMsjsPendientes;
    }
}
