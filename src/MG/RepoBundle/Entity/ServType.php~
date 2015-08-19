<?php

namespace MG\RepoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServType
 *
 * @ORM\Table(name="serv_type")
 * @ORM\Entity
 */
class ServType
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
     * @ORM\Column(name="display_name", type="string", length=75)
     */
    private $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="code_name", type="string", length=100)
     */
    private $codeName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @ORM\ManyToMany(targetEntity="ClientContratacion", mappedBy="serviciosSolicitados")
     */
    protected $contratacion;

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
     * Set displayName
     *
     * @param string $displayName
     * @return ServType
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set codeName
     *
     * @param string $codeName
     * @return ServType
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return ServType
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
     * Set descripcion
     *
     * @param string $descripcion
     * @return ServType
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Set contratacionId
     *
     * @param integer $contratacionId
     * @return ServType
     */
    public function setContratacionId($contratacionId)
    {
        $this->contratacionId = $contratacionId;

        return $this;
    }

    /**
     * Get contratacionId
     *
     * @return integer 
     */
    public function getContratacionId()
    {
        return $this->contratacionId;
    }

    /**
     * Set contratacion
     *
     * @param \MG\RepoBundle\Entity\ClientContratacion $contratacion
     * @return ServType
     */
    public function setContratacion(\MG\RepoBundle\Entity\ClientContratacion $contratacion = null)
    {
        $this->contratacion = $contratacion;

        return $this;
    }

    /**
     * Get contratacion
     *
     * @return \MG\RepoBundle\Entity\ClientContratacion 
     */
    public function getContratacion()
    {
        return $this->contratacion;
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
     * @param \MG\RepoBundle\Entity\ClientContratacion $contratacion
     * @return ServType
     */
    public function addContratacion(\MG\RepoBundle\Entity\ClientContratacion $contratacion)
    {
        $this->contratacion[] = $contratacion;

        return $this;
    }

    /**
     * Remove contratacion
     *
     * @param \MG\RepoBundle\Entity\ClientContratacion $contratacion
     */
    public function removeContratacion(\MG\RepoBundle\Entity\ClientContratacion $contratacion)
    {
        $this->contratacion->removeElement($contratacion);
    }
}
