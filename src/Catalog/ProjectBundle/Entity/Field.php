<?php
// src/Catalog/ProjectBundle/Entity/Field.php

namespace Catalog\ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="field")
 * @ORM\Entity(repositoryClass="Catalog\ProjectBundle\Entity\Repository\FieldRepository")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"Field"})
 */
class Field {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter alias.", groups={"Field"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"Field"}
     * )
     */
    protected $alias;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter title.", groups={"Field"})
     */
    protected $title;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;
    
    /**
    * @ORM\OneToMany(targetEntity="ProjectField", mappedBy="field", cascade={"persist", "remove"})
    */
   protected $project_fields;
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->project_fields = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set alias
     *
     * @param string $alias
     * @return Field
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Field
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Field
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Add project_fields
     *
     * @param \Catalog\ProjectBundle\Entity\ProjectField $projectField
     * @return Field
     */
    public function addProjectField(\Catalog\ProjectBundle\Entity\ProjectField $projectField)
    {
        $this->project_fields[] = $projectField;

        return $this;
    }

    /**
     * Remove project_fields
     *
     * @param \Catalog\FormBundle\Entity\ProjectField $projectField
     */
    public function removeProjectField(\Catalog\ProjectBundle\Entity\ProjectField $projectField)
    {
        $this->project_fields->removeElement($projectField);
    }

    /**
     * Get project_fields
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProjectFields()
    {
        return $this->project_fields;
    }
    
    public function __toString() {
        return $this->getTitle();
    }
}
