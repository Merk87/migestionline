<?php

namespace MG\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rol
 *
 * @ORM\Table(name="rol")
 * @ORM\Entity
 */
class Rol
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
     * @ORM\Column(name="rol_name", type="string", length=255)
     */
    private $rolName;

    /**
     * @var string
     *
     * @ORM\Column(name="display_rol", type="string", length=255)
     */
    private $displayRol;


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
     * Set rolName
     *
     * @param string $rolName
     * @return Rol
     */
    public function setRolName($rolName)
    {
        $this->rolName = $rolName;

        return $this;
    }

    /**
     * Get rolName
     *
     * @return string 
     */
    public function getRolName()
    {
        return $this->rolName;
    }

    /**
     * Set displayRol
     *
     * @param string $displayRol
     * @return Rol
     */
    public function setDisplayRol($displayRol)
    {
        $this->displayRol = $displayRol;

        return $this;
    }

    /**
     * Get displayRol
     *
     * @return string 
     */
    public function getDisplayRol()
    {
        return $this->displayRol;
    }
}
