<?php

namespace MG\RepoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categoria
 *
 * @ORM\Table(name="categoria")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="MG\RepoBundle\Entity\CategoriaRepository")
 */
class Categoria
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
     * @var integer
     *
     * @ORM\Column(name="id_servicio", type="integer")
     */
    protected $idServicio;

    /**
     * @ORM\ManyToOne(targetEntity="Servicios", inversedBy="categoria") 
     * @ORM\JoinColumn(name="id_servicio", referencedColumnName="id")
     */
    protected $servicio;

    /**
     *  @ORM\OneToMany(targetEntity="Gestion", mappedBy="categoria")
     */
    protected $gestion;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255)
     */
    protected $descripcion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     *
     */
    protected $enabled;

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gestion = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Categoria
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
     * Set idServicio
     *
     * @param integer $idServicio
     * @return Categoria
     */
    public function setIdServicio($idServicio)
    {
        $this->idServicio = $idServicio;

        return $this;
    }

    /**
     * Get idServicio
     *
     * @return integer 
     */
    public function getIdServicio()
    {
        return $this->idServicio;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Categoria
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
     * @return Categoria
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
     * Set servicio
     *
     * @param \MG\RepoBundle\Entity\Servicios $servicio
     * @return Categoria
     */
    public function setServicio(\MG\RepoBundle\Entity\Servicios $servicio = null)
    {
        $this->servicio = $servicio;

        return $this;
    }

    /**
     * Get servicio
     *
     * @return \MG\RepoBundle\Entity\Servicios 
     */
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * Add gestion
     *
     * @param \MG\RepoBundle\Entity\Gestion $gestion
     * @return Categoria
     */
    public function addGestion(\MG\RepoBundle\Entity\Gestion $gestion)
    {
        $this->gestion[] = $gestion;

        return $this;
    }

    /**
     * Remove gestion
     *
     * @param \MG\RepoBundle\Entity\Gestion $gestion
     */
    public function removeGestion(\MG\RepoBundle\Entity\Gestion $gestion)
    {
        $this->gestion->removeElement($gestion);
    }

    /**
     * Get gestion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGestiones()
    {
        return $this->gestion;
    }


    /**
     * Get gestion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGestion()
    {
        return $this->gestion;
    }
}
