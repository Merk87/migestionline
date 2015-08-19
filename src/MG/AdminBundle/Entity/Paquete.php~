<?php

namespace MG\AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Paquete
 *
 * @ORM\Table(name="paquete")
 * @ORM\Entity
 */
class Paquete
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
     * @ORM\Column(name="display_nombre", type="string", length=255)
     */
    private $displayNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="code_name", type="string", length=255)
     */
    private $codeName;

    /**
     * @var string
     *
     * @ORM\Column(name="display_form", type="string", length=255, nullable=true)
     */
    private $displayForm;

    /**
     * @var float
     *
     * @ORM\Column(name="precio", type="decimal", scale=2)
     */
    private $precio;

    /**
     * @ORM\OneToMany(targetEntity="Contratacion", mappedBy="paquete")
     *
     */
    private $contratacion;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDisplayForm($this->getDisplayNombre().' -- '.$this->getPrecio().'€ / mes.');
        $this->contratacion = new ArrayCollection();
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
     * Set displayNombre
     *
     * @param string $displayNombre
     * @return Paquete
     */
    public function setDisplayNombre($displayNombre)
    {
        $this->displayNombre = $displayNombre;

        return $this;
    }

    /**
     * Get displayNombre
     *
     * @return string 
     */
    public function getDisplayNombre()
    {
        return $this->displayNombre;
    }

    /**
     * Set codeName
     *
     * @param string $codeName
     * @return Paquete
     */
    public function setCodeName($codeName)
    {
        $this->codeName = $codeName;

        return $this;
    }

    /**
     * Get codeName
     *
     * @return string 
     */
    public function getCodeName()
    {
        return $this->codeName;
    }

    /**
     * Set precio
     *
     * @param float $precio
     * @return Paquete
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Add contratacion
     *
     * @param \MG\AdminBundle\Entity\Contratacion $contratacion
     * @return Paquete
     */
    public function addContratacion(\MG\AdminBundle\Entity\Contratacion $contratacion)
    {
        $this->contratacion[] = $contratacion;

        return $this;
    }

    /**
     * Remove contratacion
     *
     * @param \MG\AdminBundle\Entity\Contratacion $contratacion
     */
    public function removeContratacion(\MG\AdminBundle\Entity\Contratacion $contratacion)
    {
        $this->contratacion->removeElement($contratacion);
    }

    /**
     * Get contratacion
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContratacion()
    {
        return $this->contratacion;
    }

    /**
     * Set displayForm
     *
     * @param string $displayForm
     * @return Paquete
     */
    public function setDisplayForm($displayForm)
    {
        $this->displayForm = $displayForm;

        return $this;
    }

    /**
     * Get displayForm
     *
     * @return string 
     */
    public function getDisplayForm()
    {
        return $this->displayForm;
    }
}
