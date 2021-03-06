<?php

namespace MG\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use MG\LandingFrontBundle\Entity\RespuestaContacto;
use MG\MensajeriaBundle\Entity\Conversaciones;
use MG\MensajeriaBundle\Entity\Mensajes;
use MG\MensajeriaBundle\Entity\Notificaciones;
use MG\RepoBundle\Entity\Archivo;
use MG\RepoBundle\Entity\ClientContratacion;
use MG\RepoBundle\Entity\Comentarios;
use MG\RepoBundle\Entity\Gestion;
use MG\RepoBundle\Entity\Repo;
use MG\RepoBundle\Entity\Servicios;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


use MG\AdminBundle\Entity\Empresa;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 * @UniqueEntity(fields="nif", message="El NIF ya existe en la base de datos")
 * @ORM\Entity(repositoryClass="MG\UserBundle\Entity\UserRepository"))
 */
class User extends BaseUser
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     *
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=255, nullable=true)
     *
     */
    private $apellidos;

    /**
     * @var \DateTime
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     * @Assert\Date()
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_mvl", type="string", length=255, nullable=true)
     */
    private $telefonoMvl;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */

    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=255, nullable=true)
     */

    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_postal", type="string", length=255, nullable=true)
     */

    private $codigoPostal;

    /**
     * @var string
     *
     * @ORM\Column(name="pais", type="string", length=255, nullable=true)
     */

    private $pais;

    /**
     * @var string
     * @Assert\NotBlank(message="El nif no puede estar en blanco")
     * @ORM\Column(name="nif", type="string", length=9, nullable=false, unique=true)
     */
    protected $nif;

    /**
     *
     * @ORM\ManyToMany(targetEntity="MG\AdminBundle\Entity\Empresa", inversedBy="users")
     * @ORM\JoinTable(name="users_empresas")
     */
    protected $empresas;

    /**
     *
     * @ORM\ManyToMany(targetEntity="MG\RepoBundle\Entity\Repo", mappedBy="users")
     */
    protected $repo_users;

    /**
     *
     * @ORM\ManyToMany(targetEntity="MG\RepoBundle\Entity\Repo", mappedBy="clientes")
     */
    protected $repo_client;

    /**
     * @var integer
     *
     * @ORM\Column(name="rol_id", type="integer")
     */
    private $rolId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="nuevo", type="boolean")
     */
    private $nuevo;

    /**
    * @ORM\ManyToMany(targetEntity="MG\RepoBundle\Entity\Servicios", mappedBy="users")
    */
    protected $serv_users;

    /**
    * @ORM\ManyToMany(targetEntity="MG\RepoBundle\Entity\Servicios", mappedBy="clientes")
    */
    protected $serv_clientes;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Rol")
     * @ORM\JoinColumn(name="rol_id", referencedColumnName="id")
     */
    private $rol;

    /**
     * @ORM\OneToMany(targetEntity="MG\RepoBundle\Entity\Gestion", mappedBy="cliente")
     */
    protected $gestiones;

    /**
     * @ORM\OneToMany(targetEntity="MG\RepoBundle\Entity\Archivo", mappedBy="user")
     */
    protected $archivos;

    /**
     * @ORM\OneToMany(targetEntity="MG\RepoBundle\Entity\Comentarios", mappedBy="autor")
     */
    protected $comentarios;

    /**
     * @ORM\OneToMany(targetEntity="MG\MensajeriaBundle\Entity\Mensajes", mappedBy="remitente")
     */
    protected $mensajesAsSender;

    /**
     * @ORM\OneToMany(targetEntity="MG\MensajeriaBundle\Entity\Mensajes", mappedBy="destinatario")
     */
    protected $mensajesAsReceiver;

    /**
     * @ORM\OneToMany(targetEntity="MG\LandingFrontBundle\Entity\RespuestaContacto", mappedBy="autor")
     */
    protected $autorRespuestaContacto;

    /**
     * @ORM\ManyToMany(targetEntity="MG\MensajeriaBundle\Entity\Conversaciones", mappedBy="members")
     **/
    protected $conversaciones;

    /**
     * @ORM\OneToMany(targetEntity="MG\MensajeriaBundle\Entity\Notificaciones", mappedBy="destinatarioCliente")
     */
    protected $notificacionAsReciever;

    /**
     * @ORM\OneToMany(targetEntity="MG\RepoBundle\Entity\Clientcontratacion", mappedBy="cliente")
     */
    protected $contratacionCliente;

    public function __construct()
    {
        parent::__construct();
        $this -> empresas = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     * @return User
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     * @return User
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime 
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return User
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set telefonoMvl
     *
     * @param string $telefonoMvl
     * @return User
     */
    public function setTelefonoMvl($telefonoMvl)
    {
        $this->telefonoMvl = $telefonoMvl;

        return $this;
    }

    /**
     * Get telefonoMvl
     *
     * @return string 
     */
    public function getTelefonoMvl()
    {
        return $this->telefonoMvl;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return User
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     * @return User
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string 
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set codigoPostal
     *
     * @param string $codigoPostal
     * @return User
     */
    public function setCodigoPostal($codigoPostal)
    {
        $this->codigoPostal = $codigoPostal;

        return $this;
    }

    /**
     * Get codigoPostal
     *
     * @return string 
     */
    public function getCodigoPostal()
    {
        return $this->codigoPostal;
    }

    /**
     * Set pais
     *
     * @param string $pais
     * @return User
     */
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Get pais
     *
     * @return string 
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Set rolId
     *
     * @param integer $rolId
     * @return User
     */
    public function setRolId($rolId)
    {
        $this->rolId = $rolId;

        return $this;
    }

    /**
     * Get rolId
     *
     * @return integer 
     */
    public function getRolId()
    {
        return $this->rolId;
    }

    /**
     * Set rol
     *
     * @param \MG\UserBundle\Entity\Rol $rol
     * @return User
     */
    public function setRol(Rol $rol = null)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol
     *
     * @return \MG\UserBundle\Entity\Rol 
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Add empresas
     *
     * @param \MG\AdminBundle\Entity\Empresa $empresas
     * @return User
     */
    public function addEmpresa(Empresa $empresas)
    {
        $empresas->addUser($this);
        $this->empresas[] = $empresas;
    }

    /**
     * Remove empresas
     *
     * @param \MG\AdminBundle\Entity\Empresa $empresas
     */
    public function removeEmpresa(Empresa $empresas)
    {

        $this->cleanUserOnUnassign($empresas);

        $this->empresas->removeElement($empresas);
    }

    /**
     * Get empresas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmpresas()
    {
        return $this->empresas;
    }

     /**
     * Set nif
     *
     * @param string $nif
     * @return User
     */
    public function setNif($nif)
    {
        $this->nif = $nif;

        return $this;
    }

    /**
     * Get nif
     *
     * @return string 
     */
    public function getNif()
    {
        return $this->nif;
    }

     /**
     * Add repo_users
     *
     * @param \MG\RepoBundle\Entity\Repo $repoUsers
     * @return User
     */
    public function addRepoUser(Repo $repoUsers)
    {
        $this->repo_users[] = $repoUsers;

        return $this;
    }

    /**
     * Remove repo_users
     *
     * @param \MG\RepoBundle\Entity\Repo $repoUsers
     */
    public function removeRepoUser(Repo $repoUsers)
    {
        $this->repo_users->removeElement($repoUsers);
    }

    /**
     * Get repo_users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRepoUsers()
    {
        return $this->repo_users;
    }

    /**
     * Add repo_client
     *
     * @param \MG\RepoBundle\Entity\Repo $repoClient
     * @return User
     */
    public function addRepoClient(Repo $repoClient)
    {
        $this->repo_client[] = $repoClient;

        return $this;
    }

    /**
     * Remove repo_client
     *
     * @param \MG\RepoBundle\Entity\Repo $repoClient
     */
    public function removeRepoClient(Repo $repoClient)
    {
        $this->repo_client->removeElement($repoClient);
    }

    /**
     * Get repo_client
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRepoClient()
    {
        return $this->repo_client;
    }

    public function getRepos()
    {
        $repos = array();
        foreach ($this->getRepoClient() as $repC) {
            $repos[] = $repC;
        }

        foreach ($this->getRepoUsers() as $repU) {
            $repos[] = $repU;
        }

        return $repos;
    }

    /**
     * Add archivos
     *
     * @param \MG\RepoBundle\Entity\Archivo $archivos
     * @return User
     */
    public function addArchivo(Archivo $archivos)
    {
        $this->archivos[] = $archivos;

        return $this;
    }

    /**
     * Remove archivos
     *
     * @param \MG\RepoBundle\Entity\Archivo $archivos
     */
    public function removeArchivo(Archivo $archivos)
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
     * Add serv_clientes
     *
     * @param \MG\RepoBundle\Entity\Servicios $servClientes
     * @return User
     */
    public function addServCliente(Servicios $servClientes)
    {
        $this->serv_clientes[] = $servClientes;

        return $this;
    }

    /**
     * Remove serv_clientes
     *
     * @param \MG\RepoBundle\Entity\Servicios $servClientes
     */
    public function removeServCliente(Servicios $servClientes)
    {
        $this->serv_clientes->removeElement($servClientes);
    }

    /**
     * Get serv_clientes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServClientes()
    {
        return $this->serv_clientes;
    }

    /**
     * Add serv_users
     *
     * @param \MG\RepoBundle\Entity\Servicios $servUsers
     * @return User
     */
    public function addServUser(Servicios $servUsers)
    {
        $this->serv_users[] = $servUsers;

        return $this;
    }

    /**
     * Remove serv_users
     *
     * @param \MG\RepoBundle\Entity\Servicios $servUsers
     */
    public function removeServUser(Servicios $servUsers)
    {
        $this->serv_users->removeElement($servUsers);
    }


    /**
     * Get serv_users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServUsers()
    {
        return $this->serv_users;
    }

    /**
     * Add comentarios
     *
     * @param \MG\RepoBundle\Entity\Comentarios $comentarios
     * @return User
     */
    public function addComentario(Comentarios $comentarios)
    {
        $this->comentarios[] = $comentarios;

        return $this;
    }

    /**
     * Remove comentarios
     *
     * @param \MG\RepoBundle\Entity\Comentarios $comentarios
     */
    public function removeComentario(Comentarios $comentarios)
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
     * Add gestiones
     *
     * @param \MG\RepoBundle\Entity\Gestion $gestiones
     * @return User
     */
    public function addGestione(Gestion $gestiones)
    {
        $this->gestiones[] = $gestiones;

        return $this;
    }

    /**
     * Remove gestiones
     *
     * @param \MG\RepoBundle\Entity\Gestion $gestiones
     */
    public function removeGestione(Gestion $gestiones)
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
     * Add mensajesAsSender
     *
     * @param \MG\MensajeriaBundle\Entity\Mensajes $mensajesAsSender
     * @return User
     */
    public function addMensajesAsSender(Mensajes $mensajesAsSender)
    {
        $this->mensajesAsSender[] = $mensajesAsSender;

        return $this;
    }

    /**
     * Remove mensajesAsSender
     *
     * @param \MG\MensajeriaBundle\Entity\Mensajes $mensajesAsSender
     */
    public function removeMensajesAsSender(Mensajes $mensajesAsSender)
    {
        $this->mensajesAsSender->removeElement($mensajesAsSender);
    }

    /**
     * Get mensajesAsSender
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMensajesAsSender()
    {
        return $this->mensajesAsSender;
    }

    /**
     * Add mensajesAsReceiver
     *
     * @param \MG\MensajeriaBundle\Entity\Mensajes $mensajesAsReceiver
     * @return User
     */
    public function addMensajesAsReceiver(Mensajes $mensajesAsReceiver)
    {
        $this->mensajesAsReceiver[] = $mensajesAsReceiver;

        return $this;
    }

    /**
     * Remove mensajesAsReceiver
     *
     * @param \MG\MensajeriaBundle\Entity\Mensajes $mensajesAsReceiver
     */
    public function removeMensajesAsReceiver(Mensajes $mensajesAsReceiver)
    {
        $this->mensajesAsReceiver->removeElement($mensajesAsReceiver);
    }

    /**
     * Get mensajesAsReceiver
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMensajesAsReceiver()
    {
        return $this->mensajesAsReceiver;
    }

    /**
     * Add conversaciones
     *
     * @param \MG\MensajeriaBundle\Entity\Conversaciones $conversaciones
     * @return User
     */
    public function addConversacione(Conversaciones $conversaciones)
    {
        $this->conversaciones[] = $conversaciones;

        return $this;
    }

    /**
     * Remove conversaciones
     *
     * @param \MG\MensajeriaBundle\Entity\Conversaciones $conversaciones
     */
    public function removeConversacione(Conversaciones $conversaciones)
    {
        $this->conversaciones->removeElement($conversaciones);
    }

    /**
     * Get conversaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConversaciones()
    {
        return $this->conversaciones;
    }

    /**
     * Add notificacionAsReciever
     *
     * @param \MG\MensajeriaBundle\Entity\Notificaciones $notificacionAsReciever
     * @return User
     */
    public function addNotificacionAsReciever(Notificaciones $notificacionAsReciever)
    {
        $this->notificacionAsReciever[] = $notificacionAsReciever;

        return $this;
    }

    /**
     * Remove notificacionAsReciever
     *
     * @param \MG\MensajeriaBundle\Entity\Notificaciones $notificacionAsReciever
     */
    public function removeNotificacionAsReciever(Notificaciones $notificacionAsReciever)
    {
        $this->notificacionAsReciever->removeElement($notificacionAsReciever);
    }

    /**
     * Get notificacionAsReciever
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotificacionAsReciever()
    {
        return $this->notificacionAsReciever;
    }

    /**
     * Add autorRespuestaContacto
     *
     * @param \MG\LandingFrontBundle\Entity\RespuestaContacto $autorRespuestaContacto
     * @return User
     */
    public function addAutorRespuestaContacto(RespuestaContacto $autorRespuestaContacto)
    {
        $this->autorRespuestaContacto[] = $autorRespuestaContacto;

        return $this;
    }

    /**
     * Remove autorRespuestaContacto
     *
     * @param \MG\LandingFrontBundle\Entity\RespuestaContacto $autorRespuestaContacto
     */
    public function removeAutorRespuestaContacto(RespuestaContacto $autorRespuestaContacto)
    {
        $this->autorRespuestaContacto->removeElement($autorRespuestaContacto);
    }

    /**
     * Get autorRespuestaContacto
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAutorRespuestaContacto()
    {
        return $this->autorRespuestaContacto;
    }

    /**
     * Add contratacionCliente
     *
     * @param \MG\RepoBundle\Entity\Clientcontratacion $contratacionCliente
     * @return User
     */
    public function addContratacionCliente(ClientContratacion $contratacionCliente)
    {
        $this->contratacionCliente[] = $contratacionCliente;

        return $this;
    }

    /**
     * Remove contratacionCliente
     *
     * @param \MG\RepoBundle\Entity\Clientcontratacion $contratacionCliente
     */
    public function removeContratacionCliente(Clientcontratacion $contratacionCliente)
    {
        $this->contratacionCliente->removeElement($contratacionCliente);
    }

    /**
     * Get contratacionCliente
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContratacionCliente()
    {
        return $this->contratacionCliente;
    }

    /**
     * Set nuevo
     *
     * @param boolean $nuevo
     * @return User
     */
    public function setNuevo($nuevo)
    {
        $this->nuevo = $nuevo;

        return $this;
    }

    /**
     * Get nuevo
     *
     * @return boolean 
     */
    public function getNuevo()
    {
        return $this->nuevo;
    }

}
