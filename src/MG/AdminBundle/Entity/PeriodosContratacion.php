<?php

namespace MG\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeriodosContratacion
 *
 * @ORM\Table(name="periodos_contratacion")
 * @ORM\Entity
 */
class PeriodosContratacion
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
     * @ORM\Column(name="displayDuracion", type="string", length=255)
     */
    private $displayDuracion;

    /**
     * @var integer
     *
     * @ORM\Column(name="descuento", type="integer")
     */
    private $descuento;

    /**
     * @var string
     *
     * @ORM\Column(name="displayForm", type="string", length=255)
     */
    private $displayForm;

    /**
     * @var integer
     *
     * @ORM\Column(name="duracion", type="integer")
     */
    private $duracion;

    /**
     * @ORM\OneToMany(targetEntity="Contratacion", mappedBy="periodo")
     *
     */
    private $contratacion;

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
     * Set displayDuracion
     *
     * @param string $displayDuracion
     * @return PeriodosContratacion
     */
    public function setDisplayDuracion($displayDuracion)
    {
        $this->displayDuracion = $displayDuracion;

        return $this;
    }

    /**
     * Get displayDuracion
     *
     * @return string 
     */
    public function getDisplayDuracion()
    {
        return $this->displayDuracion;
    }

    /**
     * Set descuento
     *
     * @param integer $descuento
     * @return PeriodosContratacion
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get descuento
     *
     * @return integer 
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set displayForm
     *
     * @param string $displayForm
     * @return PeriodosContratacion
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

    /**
     * Set duracion
     *
     * @param integer $duracion
     * @return PeriodosContratacion
     */
    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;

        return $this;
    }

    /**
     * Get duracion
     *
     * @return integer 
     */
    public function getDuracion()
    {
        return $this->duracion;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratacion = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add contratacion
     *
     * @param \MG\AdminBundle\Entity\Contratacion $contratacion
     * @return PeriodosContratacion
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
}
