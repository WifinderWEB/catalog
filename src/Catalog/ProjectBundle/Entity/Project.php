<?php

//src/Catalog/ProjectBundle/Entity/Project.php

namespace Catalog\ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Catalog\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="Catalog\ProjectBundle\Entity\Repository\ProjectRepository")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"Project"})
 * @ORM\HasLifecycleCallbacks
 */
class Project {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $title;
    
    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"Project"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"Project"}
     * )
     */
    protected $alias;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;
    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $image;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image_path;
    
    protected $delete_image = false;
    
    protected $temp_image;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;

    /**
     * @ORM\ManyToMany(targetEntity="Catalog\UserBundle\Entity\User", mappedBy="projects")
     */
    protected $users;
    
    /**
     * @ORM\OneToMany(targetEntity="Catalog\CatalogBundle\Entity\CatalogProject", mappedBy="project", cascade={"remove", "persist"})
     */
    protected $join_catalog;

    /**
     * @ORM\OneToMany(targetEntity="Catalog\OrderBundle\Entity\Order", mappedBy="project", cascade={"remove", "persist"})
     */
    protected $join_order;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="Catalog\ContentBundle\Entity\Content", 
     *   mappedBy="project", 
     *   cascade={"persist", "remove"})
    */
    protected $contents;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="Catalog\TagBundle\Entity\Tag", 
     *   mappedBy="project", 
     *   cascade={"persist", "remove"})
    */
    protected $tags;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="Catalog\ImageGalleryBundle\Entity\ImageCategory", 
     *   mappedBy="project", 
     *   cascade={"persist", "remove"})
    */
    protected $images;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="Catalog\VideoGalleryBundle\Entity\VideoCategory", 
     *   mappedBy="project", 
     *   cascade={"persist", "remove"})
    */
    protected $videos;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="Catalog\FileGalleryBundle\Entity\File", 
     *   mappedBy="project", 
     *   cascade={"persist", "remove"})
    */
    protected $files;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="Catalog\FileGalleryBundle\Entity\FileCategory", 
     *   mappedBy="project", 
     *   cascade={"persist", "remove"})
    */
    protected $categories_file;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="\Catalog\ProjectBundle\Entity\ProjectField", 
     *   mappedBy="project", 
     *   cascade={"persist", "remove"})
    */
    protected $fields;
    
    public function getAbsolutePath()
    {
        return null === $this->image_path ? null : $this->getUploadRootDir().'/'.$this->image_path;
    }
    
    public function getWebPath() {
        return null === $this->image_path ? '/images/default_project.jpg' : '/' . $this->getUploadDir() . '/' . $this->image_path;
    }

    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        return 'uploads/images';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->getImage()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->image_path = $filename.'.'.$this->getImage()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->image) {
            return;
        }

        if (null !== $this->getImage()){
            $this->getImage()->move($this->getUploadRootDir(), $this->image_path);
            if ($this->temp_image) { 
                // delete the old image
                $file = $this->getUploadRootDir().'/'.$this->temp_image;
                if(file_exists($file))
                    unlink($file);
                // clear the temp image path
                $this->temp_image = null;
            }
            $this->image = null;
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($image = $this->getAbsolutePath()) {
            if(file_exists($image))
                unlink($image);
        }
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
     * @return Project
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
     * @return Project
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
     * @return Project
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
    
    public function __toString() {
        return $this->title;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fields = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add users
     *
     * @param \Catalog\UserBundle\Entity\User $users
     * @return Project
     */
    public function addUser(\Catalog\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param \Catalog\UserBundle\Entity\User $users
     */
    public function removeUser(\Catalog\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
    
    /**
     * Set image_path
     *
     * @param string $image_path
     * @return Project
     */
    public function setImagePath($image_path)
    {
        $this->image_path = $image_path;
    
        return $this;
    }

    /**
     * Get image_path
     *
     * @return string 
     */
    public function getImagePath()
    {
        return $this->image_path;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $image
     */
    public function setImage(UploadedFile $image = null) {
        $this->image = $image;
        // check if we have an old image path
        if (isset($this->image_path)) {
            // store the old name to delete after the update
            $this->temp_image = $this->image_path;
            $this->image_path = null;
        } else {
            $this->image_path = 'initial';
        }
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage() {
        return $this->image;
    }
    
    public function getDeleteImage(){
        return $this->delete_image;
    }
    
    public function setDeleteImage($delete){
        if($delete && null === $this->getImage()){
            if ($file = $this->getAbsolutePath()) {
                if(file_exists($file))
                    unlink($file);
                $this->image_path = null;
            }
        }        
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
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
     * Add join_catalog
     *
     * @param \Catalog\CatalogBundle\Entity\CatalogProject $joinCatalog
     * @return Project
     */
    public function addJoinCatalog(\Catalog\CatalogBundle\Entity\CatalogProject $joinCatalog)
    {
        $this->join_catalog[] = $joinCatalog;
    
        return $this;
    }

    /**
     * Remove join_catalog
     *
     * @param \Catalog\CatalogBundle\Entity\CatalogProject $joinCatalog
     */
    public function removeJoinCatalog(\Catalog\CatalogBundle\Entity\CatalogProject $joinCatalog)
    {
        $this->join_catalog->removeElement($joinCatalog);
    }

    /**
     * Get join_catalog
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJoinCatalog()
    {
        return $this->join_catalog;
    }

    /**
     * Add contents
     *
     * @param \Catalog\ContentBundle\Entity\Content $contents
     * @return Project
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

    /**
     * Add tags
     *
     * @param \Catalog\TagBundle\Entity\Tag $tags
     * @return Project
     */
    public function addTag(\Catalog\TagBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Catalog\TagBundle\Entity\Tag $tags
     */
    public function removeTag(\Catalog\TagBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add images
     *
     * @param \Catalog\ImageGalleryBundle\Entity\ImageCategory $images
     * @return Project
     */
    public function addImage(\Catalog\ImageGalleryBundle\Entity\ImageCategory $images)
    {
        $this->images[] = $images;
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param \Catalog\ImageGalleryBundle\Entity\ImageCategory $images
     */
    public function removeImage(\Catalog\ImageGalleryBundle\Entity\ImageCategory $images)
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
     * Add videos
     *
     * @param \Catalog\VideoGalleryBundle\Entity\VideoCategory $videos
     * @return Project
     */
    public function addVideo(\Catalog\VideoGalleryBundle\Entity\VideoCategory $videos)
    {
        $this->videos[] = $videos;
    
        return $this;
    }

    /**
     * Remove videos
     *
     * @param \Catalog\VideoGalleryBundle\Entity\VideoCategory $videos
     */
    public function removeVideo(\Catalog\VideoGalleryBundle\Entity\VideoCategory $videos)
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
     * Add files
     *
     * @param \Catalog\FileGalleryBundle\Entity\File $files
     * @return Project
     */
    public function addFile(\Catalog\FileGalleryBundle\Entity\File $files)
    {
        $this->files[] = $files;
    
        return $this;
    }

    /**
     * Remove files
     *
     * @param \Catalog\FileGalleryBundle\Entity\File $files
     */
    public function removeFile(\Catalog\FileGalleryBundle\Entity\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add categories_file
     *
     * @param \Catalog\FileGalleryBundle\Entity\FileCategory $categoriesFile
     * @return Project
     */
    public function addCategoriesFile(\Catalog\FileGalleryBundle\Entity\FileCategory $categoriesFile)
    {
        $this->categories_file[] = $categoriesFile;
    
        return $this;
    }

    /**
     * Remove categories_file
     *
     * @param \Catalog\FileGalleryBundle\Entity\FileCategory $categoriesFile
     */
    public function removeCategoriesFile(\Catalog\FileGalleryBundle\Entity\FileCategory $categoriesFile)
    {
        $this->categories_file->removeElement($categoriesFile);
    }

    /**
     * Get categories_file
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategoriesFile()
    {
        return $this->categories_file;
    }

    /**
     * Add fields
     *
     * @param \Catalog\ProjectBundle\Entity\ProjectField $fields
     * @return Project
     */
    public function addField(\Catalog\ProjectBundle\Entity\ProjectField $fields)
    {
        $this->fields[] = $fields;
        $fields->setProject($this);
        return $this;
    }

    /**
     * Remove fields
     *
     * @param \Catalog\ProjectBundle\Entity\ProjectField $fields
     */
    public function removeField(\Catalog\ProjectBundle\Entity\ProjectField $fields)
    {
        $this->fields->removeElement($fields);
    }

    /**
     * Get fields
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Add join_order
     *
     * @param \Catalog\OrderBundle\Entity\Order $joinOrder
     * @return Project
     */
    public function addJoinOrder(\Catalog\OrderBundle\Entity\Order $joinOrder)
    {
        $this->join_order[] = $joinOrder;
    
        return $this;
    }

    /**
     * Remove join_order
     *
     * @param \Catalog\OrderBundle\Entity\Order $joinOrder
     */
    public function removeJoinOrder(\Catalog\OrderBundle\Entity\Order $joinOrder)
    {
        $this->join_order->removeElement($joinOrder);
    }

    /**
     * Get join_order
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJoinOrder()
    {
        return $this->join_order;
    }
}