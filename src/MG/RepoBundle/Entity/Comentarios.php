<?php

namespace MG\RepoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comentarios
 *
 * @ORM\Table(name="comentarios")
 * @ORM\Entity
 */
class Comentarios
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
     * @ORM\ManyToOne(targetEntity="MG\UserBundle\Entity\User", inversedBy="comentarios")
     * @ORM\JoinColumn(name="id_autor", referencedColumnName="id")
     */
    private $autor;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_gestion", type="integer")
     */
    private $idGestion;

    /**
     * @ORM\ManyToOne(targetEntity="Gestion", inversedBy="comentarios")
     * @ORM\JoinColumn(name="id_gestion", referencedColumnName="id")
     */
    private $gestion;

    /**
     * @var string
     *
     * @ORM\Column(name="Comentario", type="text")
     */
    private $comentario;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipoAutor", type="integer")
     */
    private $tipoAutor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_status", type="integer", nullable=false)
     */
    protected $idStatus;

    /**
     * @ORM\ManyToOne(targetEntity="MG\MensajeriaBundle\Entity\Status", inversedBy="comentario")
     * @ORM\JoinColumn(name="id_status", referencedColumnName="id")
     */
    protected $status;

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
     * Set idGestion
     *
     * @param integer $idGestion
     * @return Comentarios
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
     * Set comentario
     *
     * @param string $comentario
     * @return Comentarios
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string 
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set tipoAutor
     *
     * @param integer $tipoAutor
     * @return Comentarios
     */
    public function setTipoAutor($tipoAutor)
    {
        $this->tipoAutor = $tipoAutor;

        return $this;
    }

    /**
     * Get tipoAutor
     *
     * @return integer 
     */
    public function getTipoAutor()
    {
        return $this->tipoAutor;
    }

    /**
     * Set gestion
     *
     * @param \MG\RepoBundle\Entity\Gestion $gestion
     * @return Comentarios
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
     * Set idAutor
     *
     * @param integer $idAutor
     * @return Comentarios
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
     * Set autor
     *
     * @param \MG\UserBundle\Entity\User $autor
     * @return Comentarios
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Comentarios
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set idStatus
     *
     * @param integer $idStatus
     * @return Comentarios
     */
    public function setIdStatus($idStatus)
    {
        $this->idStatus = $idStatus;

        return $this;
    }

    /**
     * Get idStatus
     *
     * @return integer 
     */
    public function getIdStatus()
    {
        return $this->idStatus;
    }

    /**
     * Set status
     *
     * @param \MG\MensajeriaBundle\Entity\Status $status
     * @return Comentarios
     */
    public function setStatus(\MG\MensajeriaBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \MG\MensajeriaBundle\Entity\Status 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
