<?php

namespace MG\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estado
 *
 * @ORM\Table(name="comunidada")
 * @ORM\Entity
 */
class ComunidadA
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
     * @ORM\OneToMany(targetEntity="Provincia", mappedBy="comunidadA")
     */
    protected $provincia;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_pais", type="integer", nullable=false)
     */
    protected $idPais;

    /**
     * @ORM\ManyToOne(targetEntity="Pais", inversedBy="comunidada")
     * @ORM\JoinColumn(name="id_pais", referencedColumnName="id")
     */
    protected $pais;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->provincia = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ComunidadA
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
     * Set idPais
     *
     * @param integer $idPais
     * @return ComunidadA
     */
    public function setIdPais($idPais)
    {
        $this->idPais = $idPais;

        return $this;
    }

    /**
     * Get idPais
     *
     * @return integer 
     */
    public function getIdPais()
    {
        return $this->idPais;
    }

    /**
     * Add provincia
     *
     * @param \MG\AdminBundle\Entity\Provincia $provincia
     * @return ComunidadA
     */
    public function addProvincium(\MG\AdminBundle\Entity\Provincia $provincia)
    {
        $this->provincia[] = $provincia;

        return $this;
    }

    /**
     * Remove provincia
     *
     * @param \MG\AdminBundle\Entity\Provincia $provincia
     */
    public function removeProvincium(\MG\AdminBundle\Entity\Provincia $provincia)
    {
        $this->provincia->removeElement($provincia);
    }

    /**
     * Get provincia
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set pais
     *
     * @param \MG\AdminBundle\Entity\Pais $pais
     * @return ComunidadA
     */
    public function setPais(\MG\AdminBundle\Entity\Pais $pais = null)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Get pais
     *
     * @return \MG\AdminBundle\Entity\Pais 
     */
    public function getPais()
    {
        return $this->pais;
    }
}
