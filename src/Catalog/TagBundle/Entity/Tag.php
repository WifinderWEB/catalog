<?php

//src/Catalog/TagBundle/Entity/Tag.php

namespace Catalog\TagBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="Catalog\TagBundle\Entity\Repository\TagRepository")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"Tag"})
 */
class Tag {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank(message="Please enter alias.", groups={"Tag"})
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"Tag"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"Tag"}
     * )
     */
    protected $alias;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;
    
    /**
     * @ORM\ManyToMany(targetEntity="Catalog\ContentBundle\Entity\Content", mappedBy="tags")
     */
    protected $contents;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Catalog\ProjectBundle\Entity\Project", 
     *   inversedBy="tags")
     * @ORM\JoinColumn(
     *   name="project_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $project;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contents = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Tag
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
     * @return Tag
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
     * Set is_active
     *
     * @param boolean $isActive
     * @return Tag
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
     * Add contents
     *
     * @param \Catalog\ContentBundle\Entity\Content $contents
     * @return Tag
     */
    public function addContent(\Catalog\ContentBundle\Entity\Content $contents)
    {
        $this->contents[] = $contents;
    
        return $this;
    }

    /**
     * Remove contents
     *
     * @param \Catalog\ContentBundle\Entity\Content $contents
     */
    public function removeContent(\Catalog\ContentBundle\Entity\Content $contents)
    {
        $this->contents->removeElement($contents);
    }

    /**
     * Get contents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContents()
    {
        return $this->contents;
    }
    
    public function __toString() {
        return $this->getTitle();
    }
    
    /**
     * Set project
     *
     * @param \Catalog\ProjectBundle\Entity\Project $project
     * @return Tag
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
