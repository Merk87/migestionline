<?php

namespace MG\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pais
 *
 * @ORM\Table(name="pais")
 * @ORM\Entity
 */
class Pais
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
     * @ORM\OneToMany(targetEntity="ComunidadA", mappedBy="pais")
     */
    protected $comunidada;

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
     * @return Pais
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
     * Constructor
     */
    public function __construct()
    {
        $this->comunidada = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add comunidada
     *
     * @param \MG\AdminBundle\Entity\ComunidadA $comunidada
     * @return Pais
     */
    public function addComunidada(\MG\AdminBundle\Entity\ComunidadA $comunidada)
    {
        $this->comunidada[] = $comunidada;

        return $this;
    }

    /**
     * Remove comunidada
     *
     * @param \MG\AdminBundle\Entity\ComunidadA $comunidada
     */
    public function removeComunidada(\MG\AdminBundle\Entity\ComunidadA $comunidada)
    {
        $this->comunidada->removeElement($comunidada);
    }

    /**
     * Get comunidada
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComunidada()
    {
        return $this->comunidada;
    }
}
