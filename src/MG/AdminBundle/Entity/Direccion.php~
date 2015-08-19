<?php

namespace MG\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Direccion
 *
 * @ORM\Table(name="direccion")
 * @ORM\Entity
 */
class Direccion
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
     * @ORM\Column(name="tipo_via", type="string", length=255, nullable=false)
     */
    private $tipoVia;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_via", type="string", length=255, nullable=false)
     */
    private $nombreVia;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="integer" , nullable=false)
     */
    private $numero;

    /**
     * @var integer
     *
     * @ORM\Column(name="piso", type="integer")
     */
    private $piso;

    /**
     * @var string
     *
     * @ORM\Column(name="escalera", type="string", length=255)
     */
    private $escalera;

    /**
     * @var string
     *
     * @ORM\Column(name="letra", type="string", length=4)
     */
    private $letra;

    /**
     * @var integer
     *
     * @ORM\Column(name="municipio_id", type="integer")
     */
    private $idMunicipio;

    /**
     * @ORM\ManyToOne(targetEntity="Municipio", inversedBy="direccion")
     * @ORM\JoinColumn(name="municipio_id", referencedColumnName="id")
     */
    private $municipio;


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
     * Set tipoVia
     *
     * @param string $tipoVia
     * @return Direccion
     */
    public function setTipoVia($tipoVia)
    {
        $this->tipoVia = $tipoVia;

        return $this;
    }

    /**
     * Get tipoVia
     *
     * @return string 
     */
    public function getTipoVia()
    {
        return $this->tipoVia;
    }

    /**
     * Set nombreVia
     *
     * @param string $nombreVia
     * @return Direccion
     */
    public function setNombreVia($nombreVia)
    {
        $this->nombreVia = $nombreVia;

        return $this;
    }

    /**
     * Get nombreVia
     *
     * @return string 
     */
    public function getNombreVia()
    {
        return $this->nombreVia;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     * @return Direccion
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set piso
     *
     * @param integer $piso
     * @return Direccion
     */
    public function setPiso($piso)
    {
        $this->piso = $piso;

        return $this;
    }

    /**
     * Get piso
     *
     * @return integer 
     */
    public function getPiso()
    {
        return $this->piso;
    }

    /**
     * Set escalera
     *
     * @param string $escalera
     * @return Direccion
     */
    public function setEscalera($escalera)
    {
        $this->escalera = $escalera;

        return $this;
    }

    /**
     * Get escalera
     *
     * @return string 
     */
    public function getEscalera()
    {
        return $this->escalera;
    }

    /**
     * Set letra
     *
     * @param string $letra
     * @return Direccion
     */
    public function setLetra($letra)
    {
        $this->letra = $letra;

        return $this;
    }

    /**
     * Get letra
     *
     * @return string 
     */
    public function getLetra()
    {
        return $this->letra;
    }

    /**
     * Set idMunicipio
     *
     * @param integer $idMunicipio
     * @return Direccion
     */
    public function setIdMunicipio($idMunicipio)
    {
        $this->idMunicipio = $idMunicipio;

        return $this;
    }

    /**
     * Get idMunicipio
     *
     * @return integer 
     */
    public function getIdMunicipio()
    {
        return $this->idMunicipio;
    }

    /**
     * Set municipio
     *
     * @param \MG\AdminBundle\Entity\Municipio $municipio
     * @return Direccion
     */
    public function setMunicipio(\MG\AdminBundle\Entity\Municipio $municipio = null)
    {
        $this->municipio = $municipio;

        return $this;
    }

    /**
     * Get municipio
     *
     * @return \MG\AdminBundle\Entity\Municipio 
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }
}
