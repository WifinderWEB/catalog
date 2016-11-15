<?php

//src/Catalog/CatalogBundle/Entity/Catalog.php

namespace Catalog\CatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="catalog_project")
 * @ORM\Entity(repositoryClass="Catalog\CatalogBundle\Entity\Repository\CatalogProjectRepository")
 * @UniqueEntity(fields="{project, catalog}", message="Sorry, this relations is already in use.", groups={"CatalogProject"})
 */
class CatalogProject {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /** 
    * @ORM\Column(type="integer")
    */
    protected $project_id;
    /**
     * @ORM\ManyToOne(targetEntity="Catalog\ProjectBundle\Entity\Project", inversedBy="join_catalog")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $project;
    
    /** 
    * @ORM\Column(type="integer")
    */
   protected $catalog_id;
    /**
     * @ORM\ManyToOne(targetEntity="Catalog", inversedBy="join_catalog")
     * @ORM\JoinColumn(name="catalog_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $catalog;

    public function __construct($catalog, $project)
    {
        $this->catalog = $catalog;
        $this->project = $project;
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
     * Set project_id
     *
     * @param integer $projectId
     * @return CatalogProject
     */
    public function setProjectId($projectId)
    {
        $this->project_id = $projectId;
    
        return $this;
    }

    /**
     * Get project_id
     *
     * @return integer 
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * Set catalog_id
     *
     * @param integer $catalogId
     * @return CatalogProject
     */
    public function setCatalogId($catalogId)
    {
        $this->catalog_id = $catalogId;
    
        return $this;
    }

    /**
     * Get catalog_id
     *
     * @return integer 
     */
    public function getCatalogId()
    {
        return $this->catalog_id;
    }

    /**
     * Set project
     *
     * @param \Catalog\ProjectBundle\Entity\Project $project
     * @return CatalogProject
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

    /**
     * Set catalog
     *
     * @param \Catalog\CatalogBundle\Entity\Catalog $catalog
     * @return CatalogProject
     */
    public function setCatalog(\Catalog\CatalogBundle\Entity\Catalog $catalog = null)
    {
        $this->catalog = $catalog;
    
        return $this;
    }

    /**
     * Get catalog
     *
     * @return \Catalog\CatalogBundle\Entity\Catalog 
     */
    public function getCatalog()
    {
        return $this->catalog;
    }
}