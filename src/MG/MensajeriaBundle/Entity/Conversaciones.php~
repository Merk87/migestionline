<?php

namespace MG\MensajeriaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conversaciones
 *
 * @ORM\Table(name="conversaciones")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MG\MensajeriaBundle\Entity\ConversacionesRepository")
 */
class Conversaciones
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
     * @ORM\OneToMany(targetEntity="Mensajes", mappedBy="conversacion")
     */
    private $mensajes;

    /**
     * @ORM\ManyToMany(targetEntity="MG\UserBundle\Entity\User", inversedBy="conversaciones")
     * @ORM\JoinTable(name="users_conversacion")
     */
    private $members;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="datetime")
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_last", type="datetime")
     */
    private $fechaLastMessage;

    /**
     * @var string
     *
     * @ORM\Column(name="asunto", type="string")
     */
    protected $asunto;


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
     * Set mensajes
     *
     * @param integer $mensajes
     * @return Conversaciones
     */
    public function setMensajes($mensajes)
    {
        $this->mensajes = $mensajes;

        return $this;
    }

    /**
     * Get mensajes
     *
     * @return integer 
     */
    public function getMensajes()
    {
        return $this->mensajes;
    }

    /**
     * Set members
     *
     * @param integer $members
     * @return Conversaciones
     */
    public function setMembers($members)
    {
        $this->members = $members;

        return $this;
    }

    /**
     * Get members
     *
     * @return integer 
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mensajes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Conversaciones
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
     * Set fechaLastMessage
     *
     * @param \DateTime $fechaLastMessage
     * @return Conversaciones
     */
    public function setFechaLastMessage($fechaLastMessage)
    {
        $this->fechaLastMessage = $fechaLastMessage;

        return $this;
    }

    /**
     * Get fechaLastMessage
     *
     * @return \DateTime 
     */
    public function getFechaLastMessage()
    {
        return $this->fechaLastMessage;
    }

    /**
     * Add mensajes
     *
     * @param \MG\MensajeriaBundle\Entity\Mensajes $mensajes
     * @return Conversaciones
     */
    public function addMensaje(\MG\MensajeriaBundle\Entity\Mensajes $mensajes)
    {
        $this->mensajes[] = $mensajes;

        return $this;
    }

    /**
     * Remove mensajes
     *
     * @param \MG\MensajeriaBundle\Entity\Mensajes $mensajes
     */
    public function removeMensaje(\MG\MensajeriaBundle\Entity\Mensajes $mensajes)
    {
        $this->mensajes->removeElement($mensajes);
    }

    /**
     * Add members
     *
     * @param \MG\UserBundle\Entity\User $members
     * @return Conversaciones
     */
    public function addMember(\MG\UserBundle\Entity\User $members)
    {
        $this->members[] = $members;

        return $this;
    }

    /**
     * Remove members
     *
     * @param \MG\UserBundle\Entity\User $members
     */
    public function removeMember(\MG\UserBundle\Entity\User $members)
    {
        $this->members->removeElement($members);
    }

    /**
     * Set asunto
     *
     * @param string $asunto
     * @return Conversaciones
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

    public function getLastMessage()
    {
        $lastMsg = $this->getMensajes()->last();
        return $lastMsg;
    }

   public function hasUnread($user)
   {
       $mensajesPendientes = false;
       $mensajes = $this->getMensajes();

       foreach($mensajes as $m)
       {
           if($m->getLeido() == false && $m->getIdDestinatario() == $user->getId())
           {
               $mensajesPendientes = true;
           }
       }
       return $mensajesPendientes;
    }

    public function getUnread($user)
    {
        $mensajes = $this->getMensajes();

        $noLeidos = array();

        foreach($mensajes as $m)
        {
            if($m->getLeido() == false && $m->getIdDestinatario() == $user->getId())
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
}
