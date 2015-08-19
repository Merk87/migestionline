<?php

namespace MG\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ciudades
 *
 * @ORM\Table(name="municipio")
 * @ORM\Entity
 */
class Municipio
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
     * @ORM\Column(name="id_provincia", type="integer", nullable=false)
     */
    protected $idProvincia;

    /**
     * @ORM\ManyToOne(targetEntity="Provincia", inversedBy="municipio")
     * @ORM\JoinColumn(name="id_provincia", referencedColumnName="id")
     */
    protected $provincia;

    /**
     * @ORM\OneToMany(targetEntity="Direccion", mappedBy="municipio")
     */
    protected $direccion;

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
     * @return Ciudades
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
     * Set provincia
     *
     * @param string $provincia
     * @return Ciudades
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return string 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->direccion = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add direccion
     *
     * @param \MG\AdminBundle\Entity\Direccion $direccion
     * @return Municipio
     */
    public function addDireccion(\MG\AdminBundle\Entity\Direccion $direccion)
    {
        $this->direccion[] = $direccion;

        return $this;
    }

    /**
     * Remove direccion
     *
     * @param \MG\AdminBundle\Entity\Direccion $direccion
     */
    public function removeDireccion(\MG\AdminBundle\Entity\Direccion $direccion)
    {
        $this->direccion->removeElement($direccion);
    }

    /**
     * Get direccion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set idProvincia
     *
     * @param integer $idProvincia
     * @return Municipio
     */
    public function setIdProvincia($idProvincia)
    {
        $this->idProvincia = $idProvincia;

        return $this;
    }

    /**
     * Get idProvincia
     *
     * @return integer 
     */
    public function getIdProvincia()
    {
        return $this->idProvincia;
    }
}
