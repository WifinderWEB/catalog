<?php

//src/Catalog/VideoGalleryBundle/Entity/Video.php

namespace Catalog\VideoGalleryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="video_gallery")
 * @ORM\Entity(repositoryClass="Catalog\VideoGalleryBundle\Entity\Repository\VideoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Video {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $url_video;
    /**
     * 
     * @Assert\File(maxSize="6000000")
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $sort;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @Gedmo\Translatable
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
     *   targetEntity="VideoCategory", 
     *   inversedBy="videos")
     * @ORM\JoinColumn(
     *   name="category_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $category;

    private $temp_image;
    
    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getWebPath() {
        return null === $this->path ? null : '/' . $this->getUploadDir() . '/' . $this->path;
    }

    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        return 'uploads/videos';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->getImage()) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->getImage()->guessExtension();
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
            $this->getImage()->move($this->getUploadRootDir(), $this->path);
            if (isset($this->temp_image)) {
                // delete the old image
                unlink($this->getUploadRootDir().'/'.$this->temp_image);
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
     * Set path
     *
     * @param string $path
     * @return Video
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
     * Set image
     *
     * @param string $image
     * @return Image
     */
    public function setImage($image) {
        $this->image = $image;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp_image = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
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

    /**
     * Set title
     *
     * @param string $title
     * @return Video
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
     * @return Video
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
     * @return Video
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
     * @param \Catalog\VideoGalleryBundle\Entity\VideoCategory $category
     * @return Video
     */
    public function setCategory(\Catalog\VideoGalleryBundle\Entity\VideoCategory $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Catalog\VideoGalleryBundle\Entity\VideoCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * Set category_id
     *
     * @param integer $category_id
     * @return Video
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
     * @return Video
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
     * Set url_video
     *
     * @param string $urlVideo
     * @return Video
     */
    public function setUrlVideo($urlVideo)
    {
        $this->url_video = $urlVideo;
    
        return $this;
    }

    /**
     * Get url_video
     *
     * @return string 
     */
    public function getUrlVideo()
    {
        return $this->url_video;
    }
}