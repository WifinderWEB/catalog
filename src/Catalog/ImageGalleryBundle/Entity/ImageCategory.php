<?php

//src/Catalog/ImageGalleryBundle/Entity/ImageCategory.php

namespace Catalog\ImageGalleryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="image_gallery_category")
 * @ORM\Entity(repositoryClass="Catalog\ImageGalleryBundle\Entity\Repository\ImageCategoryRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"imageCategory"})
 */
class ImageCategory {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $sort;
    
    /**
     * @ORM\Column( type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"ImageCategory"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"ImageCategory"}
     * )
     */
    protected $alias;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="Image", 
     *   mappedBy="category", 
     *   cascade={"persist", "remove"})
    */
    protected $images;
    
    /**
     * @ORM\ManyToMany(targetEntity="Catalog\ContentBundle\Entity\Content", mappedBy="image_gallery")
     */
    protected $join_content;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Catalog\ProjectBundle\Entity\Project", 
     *   inversedBy="images")
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
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->join_content = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set sort
     *
     * @param integer $sort
     * @return ImageCategory
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
     * Set alias
     *
     * @param string $alias
     * @return ImageCategory
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
     * @return ImageCategory
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
     * Set description
     *
     * @param string $description
     * @return ImageCategory
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return ImageCategory
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
     * Add images
     *
     * @param \Catalog\ImageGalleryBundle\Entity\Image $images
     * @return ImageCategory
     */
    public function addImage(\Catalog\ImageGalleryBundle\Entity\Image $images)
    {
        if (!$this->images->contains($images)) {
            $this->images[] = $images;
            $images->setCategory($this);
        }

        return $this;
    }

    /**
     * Remove images
     *
     * @param \Catalog\ImageGalleryBundle\Entity\Image $images
     */
    public function removeImage(\Catalog\ImageGalleryBundle\Entity\Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }
    
    /**
     * Add join_content
     *
     * @param \Catalog\ContentBundle\Entity\Content $joinContent
     * @return ImageCategory
     */
    public function addJoinContent(\Catalog\ContentBundle\Entity\Content $joinContent)
    {
        $this->join_content[] = $joinContent;
    
        return $this;
    }

    /**
     * Remove join_content
     *
     * @param \Catalog\ContentBundle\Entity\Content $joinContent
     */
    public function removeJoinContent(\Catalog\ContentBundle\Entity\Content $joinContent)
    {
        $this->join_content->removeElement($joinContent);
    }

    /**
     * Get join_content
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJoinContent()
    {
        return $this->join_content;
    }
    
    public function __toString() {
        return $this->title;
    }
    
    /**
     * Set project
     *
     * @param \Catalog\ProjectBundle\Entity\Project $project
     * @return ImageCategory
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