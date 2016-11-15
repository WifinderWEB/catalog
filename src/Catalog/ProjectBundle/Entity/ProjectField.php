<?php
// src/Catalog/ProjectBundle/Entity/ProjectField.php

namespace Catalog\ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="project_field")
 * @ORM\Entity(repositoryClass="Catalog\ProjectBundle\Entity\Repository\ProjectFieldRepository")
 * @UniqueEntity(fields={"alias", "project"}, message="Sorry, this alias is already in use.", groups={"FormField"})
 */
class ProjectField {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Project", 
     *   inversedBy="fields")
     * @ORM\JoinColumn(
     *   name="project_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $project;
    
    /**
     * @ORM\ManyToOne(targetEntity="Field", inversedBy="project_fields")
     * @ORM\JoinColumn(name="project_field_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $field;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter title.", groups={"FormField"})
     */
    protected $title;
    
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Please enter alias.", groups={"FormField"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"FormField"}
     * )
     */
    protected $alias;
    
    /**
    * @ORM\Column(type="integer", nullable=true)
     */
    protected $sort = 100;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $default_value;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active;

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
     * Set title
     *
     * @param string $title
     * @return FormField
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
     * Set alias
     *
     * @param string $alias
     * @return FormField
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
     * Set sort
     *
     * @param integer $sort
     * @return FormField
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set default_value
     *
     * @param string $defaultValue
     * @return FormField
     */
    public function setDefaultValue($defaultValue)
    {
        $this->default_value = $defaultValue;

        return $this;
    }

    /**
     * Get default_value
     *
     * @return string 
     */
    public function getDefaultValue()
    {
        return $this->default_value;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return FormField
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
     * Set field
     *
     * @param \Catalog\ProjectBundle\Entity\Field $field
     * @return FormField
     */
    public function setField(\Catalog\ProjectBundle\Entity\Field $field = null)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return \Catalog\ProjectBundle\Entity\Field 
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set project
     *
     * @param \Catalog\ProjectBundle\Entity\Project $project
     * @return ProjectField
     */
    public function setProject(\Catalog\ProjectBundle\Entity\Project $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \Catalog\ProjectBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }
}