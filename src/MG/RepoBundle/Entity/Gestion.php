<?php

namespace MG\RepoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gestion
 *
 * @ORM\Table(name="gestion")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MG\RepoBundle\Entity\GestionRepository")
 */
class Gestion
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
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    private $descripcion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="datetime")
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_resolucion", type="datetime", nullable=true)
     */
    private $fechaResolucion;

    /**
     * @var integer
     *
     * @ORM\Column(name="categoria_id", type="integer")
     */
    private $idCategoria;

    /**
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="gestion")
     * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    private $categoria;

    /**
     * @var integer
     *
     * @ORM\Column(name="cliente_id", type="integer")
     */
    private $idCliente;

    /**
     *
     * @ORM\ManyToOne(targetEntity="MG\UserBundle\Entity\User", inversedBy="gestiones")
     * @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
     */
    protected $cliente;

    /**
     * @var integer
     *
     * @ORM\Column(name="empresa_id", type="integer")
     */
    private $idEmpresa;

    /**
     * @ORM\ManyToOne(targetEntity="MG\AdminBundle\Entity\Empresa", inversedBy="gestiones")
     * @ORM\JoinColumn(name="empresa_id", referencedColumnName="id")
     */
    protected $empresa;

    /**
     *
     * @ORM\OneToMany(targetEntity="Comentarios", mappedBy="gestion")
     */
    private $comentarios;

    /**
     * @ORM\OneToMany(targetEntity="Archivo", mappedBy="gestion")
     */ 
    protected $archivos;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado_id", type="integer")
     */
    private $estadoId;

    /**
     * @ORM\ManyToOne(targetEntity="Estado", inversedBy="gestiones")
     * @ORM\JoinColumn(name="estado_id", referencedColumnName="id")
     */
     private $estado;

    /**
     * @ORM\OneToOne(targetEntity="MG\MensajeriaBundle\Entity\Notificaciones", mappedBy="gestion")
     */
    protected $notificacion;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->archivos = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set descripcion
     *
     * @param string $descripcion
     * @return Gestion
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Gestion
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
     * Set fechaResolucion
     *
     * @param \DateTime $fechaResolucion
     * @return Gestion
     */
    public function setFechaResolucion($fechaResolucion)
    {
        $this->fechaResolucion = $fechaResolucion;

        return $this;
    }

    /**
     * Get fechaResolucion
     *
     * @return \DateTime 
     */
    public function getFechaResolucion()
    {
        return $this->fechaResolucion;
    }

    /**
     * Set idCategoria
     *
     * @param integer $idCategoria
     * @return Gestion
     */
    public function setIdCategoria($idCategoria)
    {
        $this->idCategoria = $idCategoria;

        return $this;
    }

    /**
     * Get idCategoria
     *
     * @return integer 
     */
    public function getIdCategoria()
    {
        return $this->idCategoria;
    }

    /**
     * Set idCliente
     *
     * @param integer $idCliente
     * @return Gestion
     */
    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;

        return $this;
    }

    /**
     * Get idCliente
     *
     * @return integer 
     */
    public function getIdCliente()
    {
        return $this->idCliente;
    }

    /**
     * Set idEmpresa
     *
     * @param integer $idEmpresa
     * @return Gestion
     */
    public function setIdEmpresa($idEmpresa)
    {
        $this->idEmpresa = $idEmpresa;

        return $this;
    }

    /**
     * Get idEmpresa
     *
     * @return integer 
     */
    public function getIdEmpresa()
    {
        return $this->idEmpresa;
    }

    /**
     * Set estadoId
     *
     * @param integer $estadoId
     * @return Gestion
     */
    public function setEstadoId($estadoId)
    {
        $this->estadoId = $estadoId;

        return $this;
    }

    /**
     * Get estadoId
     *
     * @return integer 
     */
    public function getEstadoId()
    {
        return $this->estadoId;
    }

    /**
     * Set categoria
     *
     * @param \MG\RepoBundle\Entity\Categoria $categoria
     * @return Gestion
     */
    public function setCategoria(\MG\RepoBundle\Entity\Categoria $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return \MG\RepoBundle\Entity\Categoria 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set cliente
     *
     * @param \MG\UserBundle\Entity\User $cliente
     * @return Gestion
     */
    public function setCliente(\MG\UserBundle\Entity\User $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \MG\UserBundle\Entity\User 
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set empresa
     *
     * @param \MG\AdminBundle\Entity\Empresa $empresa
     * @return Gestion
     */
    public function setEmpresa(\MG\AdminBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \MG\AdminBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Add comentarios
     *
     * @param \MG\RepoBundle\Entity\Comentarios $comentarios
     * @return Gestion
     */
    public function addComentario(\MG\RepoBundle\Entity\Comentarios $comentarios)
    {
        $this->comentarios[] = $comentarios;

        return $this;
    }

    /**
     * Remove comentarios
     *
     * @param \MG\RepoBundle\Entity\Comentarios $comentarios
     */
    public function removeComentario(\MG\RepoBundle\Entity\Comentarios $comentarios)
    {
        $this->comentarios->removeElement($comentarios);
    }

    /**
     * Get comentarios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Add archivos
     *
     * @param \MG\RepoBundle\Entity\Archivo $archivos
     * @return Gestion
     */
    public function addArchivo(\MG\RepoBundle\Entity\Archivo $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \MG\RepoBundle\Entity\Archivo $archivos
     */
    public function removeArchivo(\MG\RepoBundle\Entity\Archivo $archivos)
    {
        $this->archivos->removeElement($archivos);
    }

    /**
     * Get archivos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArchivos()
    {
        return $this->archivos;
    }

    /**
     * Set estado
     *
     * @param \MG\RepoBundle\Entity\Estado $estado
     * @return Gestion
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


    public function isUnsolved()
    {
        $estado = $this->getEstado();

        if($estado->getNombre() != 'Resuelta' || $estado->getNombre() != 'Cerrada sin resolver')
        {
            return true;
        }else
        {
            return false;
        }

    }


    public function isSolved()
    {
        $estado = $this->getEstado();

        if($estado->getNombre() == 'Resuelta' || $estado->getNombre() == 'Cerrada sin resolver' || $estado->getNombre() == 'Cerrada por el cliente' )
        {
            return true;
        }else
        {
            return false;
        }

    }

    /**
     * Set notificacion
     *
     * @param \MG\MensajeriaBundle\Entity\Notificaciones $notificacion
     * @return Gestion
     */
    public function setNotificacion(\MG\MensajeriaBundle\Entity\Notificaciones $notificacion = null)
    {
        $this->notificacion = $notificacion;

        return $this;
    }

    /**
     * Get notificacion
     *
     * @return \MG\MensajeriaBundle\Entity\Notificaciones 
     */
    public function getNotificacion()
    {
        return $this->notificacion;
    }
}
