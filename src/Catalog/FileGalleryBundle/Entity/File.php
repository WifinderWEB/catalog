<?php

//src/Catalog/FileGalleryBundle/Entity/File.php

namespace Catalog\FileGalleryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Table(name="file_gallery")
 * @ORM\Entity(repositoryClass="Catalog\FileGalleryBundle\Entity\Repository\FileRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields="title", message="Sorry, this file title is already in use.", groups={"File"})
 */
class File {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * 
     * @Assert\File(maxSize="6000000")
     */
    protected $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $origin_name;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $sort;
    
    /**
     * @Assert\NotBlank(message="Please enter title.", groups={"File"})
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
     * @ORM\Column(type="integer")
     */
    protected $category_id;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="FileCategory", 
     *   inversedBy="files")
     * @ORM\JoinColumn(
     *   name="category_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $category;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Catalog\ProjectBundle\Entity\Project", 
     *   inversedBy="files")
     * @ORM\JoinColumn(
     *   name="project_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $project;
    
    /**
     * @ORM\ManyToMany(targetEntity="Catalog\ContentBundle\Entity\Content", mappedBy="files")
     */
    protected $contents;
    
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getWebPath() {
        $path = '/' . $this->getUploadDir() . '/' . $this->path;
        return $path;
    }

    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        return 'uploads/documents';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->file) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->file->guessExtension();
            $this->origin_name = $this->file->getClientOriginalName();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->file) {
            return;
        }

        $this->file->move($this->getUploadRootDir(), $this->path);
        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($file = $this->getAbsolutePath()) {
            if(file_exists($file))
                unlink($file);
        }
    }
    
    /**
     * Constructor
     */
    public function __construct() {
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
     * Set path
     *
     * @param string $path
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Set file
     *
     * @param string $file
     * @return File
     */
    public function setFile($file) {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return File
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
     * @return File
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
     * @return File
     */
    public function setIsActive($isActive) {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean 
     */
    public function getIsActive() {
        return $this->is_active;
    }

    /**
     * Set category
     *
     * @param \Catalog\FileGalleryBundle\Entity\FileCategory $category
     * @return File
     */
    public function setCategory(\Catalog\FileGalleryBundle\Entity\FileCategory $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Catalog\FileGalleryBundle\Entity\FileCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * Set category_id
     *
     * @param integer $category_id
     * @return File
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    
        return $this;
    }

    /**
     * Get category_id
     *
     * @return integer 
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }
    
    /**
     * Set sort
     *
     * @param integer $sort
     * @return File
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
     * Set origin_name
     *
     * @param string $origin_name
     * @return File
     */
    public function setOriginName($origin_name)
    {
        $this->origin_name = $origin_name;
    
        return $this;
    }

    /**
     * Get origin_name
     *
     * @return string 
     */
    public function getOriginName()
    {
        return $this->origin_name;
    }
    
    /**
     * Add contents
     *
     * @param \Catalog\ContentBundle\Entity\Content $contents
     * @return File
     */
    public function addContent(\Catalog\ContentBundle\Entity\Content $contents)
    {
        $this->contents[] = $joinContent;
    
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
    
    /**
     * Set project
     *
     * @param \Catalog\ProjectBundle\Entity\Project $project
     * @return File
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
    
    public function __toString() {
        return $this->getTitle();
    }
}