<?php

namespace MG\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Provincias
 *
 * @ORM\Table(name="provincia")
 * )
 * @ORM\Entity
 */
class Provincia
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="comunidad_id", type="integer")
     */
    private $idComunidad;

    /**
     * @ORM\ManyToOne(targetEntity="ComunidadA", inversedBy="Provincia")
     * @ORM\JoinColumn(name="comunidad_id", referencedColumnName="id")
     */
    private $comunidadA;

    /**
     * @ORM\OneToMany(targetEntity="Municipio", mappedBy="provincia")
     */
    protected $municipio;

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
     * @return Provincias
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
     * Set ciudad
     *
     * @param string $ciudad
     * @return Provincias
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
     * Set pais
     *
     * @param string $pais
     * @return Provincias
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
     * Set idComunidad
     *
     * @param integer $idComunidad
     * @return Provincia
     */
    public function setIdComunidad($idComunidad)
    {
        $this->idComunidad = $idComunidad;

        return $this;
    }

    /**
     * Get idComunidad
     *
     * @return integer 
     */
    public function getIdComunidad()
    {
        return $this->idComunidad;
    }

    /**
     * Set comunidadA
     *
     * @param \MG\AdminBundle\Entity\ComunidadA $comunidadA
     * @return Provincia
     */
    public function setComunidadA(\MG\AdminBundle\Entity\ComunidadA $comunidadA = null)
    {
        $this->comunidadA = $comunidadA;

        return $this;
    }

    /**
     * Get comunidadA
     *
     * @return \MG\AdminBundle\Entity\ComunidadA 
     */
    public function getComunidadA()
    {
        return $this->comunidadA;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->municipio = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add municipio
     *
     * @param \MG\AdminBundle\Entity\Municipio $municipio
     * @return Provincia
     */
    public function addMunicipio(\MG\AdminBundle\Entity\Municipio $municipio)
    {
        $this->municipio[] = $municipio;

        return $this;
    }

    /**
     * Remove municipio
     *
     * @param \MG\AdminBundle\Entity\Municipio $municipio
     */
    public function removeMunicipio(\MG\AdminBundle\Entity\Municipio $municipio)
    {
        $this->municipio->removeElement($municipio);
    }

    /**
     * Get municipio
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }
}
