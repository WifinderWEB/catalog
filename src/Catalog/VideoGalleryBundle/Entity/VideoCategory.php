<?php

//src/Catalog/VideoGalleryBundle/Entity/VideoCategory.php

namespace Catalog\VideoGalleryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="video_gallery_category")
 * @ORM\Entity(repositoryClass="Catalog\VideoGalleryBundle\Entity\Repository\VideoCategoryRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"videoCategory"})
 */
class VideoCategory {

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
     * @Assert\NotBlank(message="Please enter alias.", groups={"VideoCategory"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"VideoCategory"}
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
     *   targetEntity="Video", 
     *   mappedBy="category", 
     *   cascade={"persist", "remove"})
    */
    protected $videos;
    
    /**
     * @ORM\ManyToMany(targetEntity="Catalog\ContentBundle\Entity\Content", mappedBy="video_gallery")
     */
    protected $join_content;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Catalog\ProjectBundle\Entity\Project", 
     *   inversedBy="videos")
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
        $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return VideoCategory
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
     * @return VideoCategory
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
     * @return VideoCategory
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
     * @return VideoCategory
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
     * @return VideoCategory
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
     * Add videos
     *
     * @param \Catalog\VideoGalleryBundle\Entity\Video $videos
     * @return VideoCategory
     */
    public function addVideo(\Catalog\VideoGalleryBundle\Entity\Video $videos)
    {
        if (!$this->videos->contains($videos)) {
            $this->videos[] = $videos;
            $videos->setCategory($this);
        }
    
        return $this;
    }

    /**
     * Remove videos
     *
     * @param \Catalog\VideoGalleryBundle\Entity\Video $videos
     */
    public function removeVideo(\Catalog\VideoGalleryBundle\Entity\Video $videos)
    {
        $this->videos->removeElement($videos);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Add join_content
     *
     * @param \Catalog\ContentBundle\Entity\Content $joinContent
     * @return VideoCategory
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
     * @return VideoCategory
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