<?php

//src/Catalog/CatalogBundle/Entity/Catalog.php

namespace Catalog\CatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="catalog")
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="Catalog\CatalogBundle\Entity\Repository\CatalogRepository")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"Catalog"})
 * @ORM\HasLifecycleCallbacks
 */
class Catalog {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"Catalog"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"Catalog"}
     * )
     */
    protected $alias;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $title;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $anons;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $show_editor_anons = true;

    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $image;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image_path;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $image_origin_name;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $show_editor_description = true;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $big_image;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $big_image_path;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $big_image_origin_name;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;
    
    /**
     * @ORM\ManyToOne(targetEntity="Catalog\ContentBundle\Entity\Content", inversedBy="catalogs")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $content;

    /**
     * @ORM\ManyToMany(targetEntity="Catalog\CatalogBundle\Entity\Catalog", mappedBy="related")
     */
    protected $parent_related;
    
    /**
     * @ORM\ManyToMany(targetEntity="Catalog\CatalogBundle\Entity\Catalog", inversedBy="parent_related")
     * @ORM\JoinTable(name="related_catalog",
     *      joinColumns={@ORM\JoinColumn(name="catalog_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="related_id", referencedColumnName="id")}
     * )
     */
    protected $related;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Catalog", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="Catalog", mappedBy="parent")
     */
    protected $children;
    
    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $level;
    
    /**
     * @ORM\OneToOne(targetEntity="CatalogMeta", 
     *   mappedBy="catalog", 
     *   cascade={"persist", "remove"})
    */
    protected $meta;
    
    protected $delete_image = false;
    
    protected $delete_big_image = false;
    
    private $temp_image;
    
    private $temp_big_image;

    /**
     * @ORM\OneToMany(targetEntity="CatalogProject", mappedBy="catalog", cascade={"remove", "persist"})
     */
    protected $join_catalog;
    
    public function getImageAbsolutePath()
    {
        return null === $this->image_path ? null : $this->getUploadRootDir().'/'.$this->image_path;
    }
    
    public function getImageWebPath() {
        return null === $this->image_path ? null : '/' . $this->getUploadDir() . '/' . $this->image_path;
    }
    
    public function getBigImageAbsolutePath()
    {
        return null === $this->big_image_path ? null : $this->getUploadRootDir().'/'.$this->big_image_path;
    }
    
    public function getBigImageWebPath() {
        return null === $this->big_image_path ? null : '/' . $this->getUploadDir() . '/' . $this->big_image_path;
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
            $this->image_origin_name = $this->getImage()->getClientOriginalName();
        }
        if (null !== $this->getBigImage()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->big_image_path = $filename.'.'.$this->getBigImage()->guessExtension();
            $this->big_image_origin_name = $this->getBigImage()->getClientOriginalName();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->getImage() && null === $this->getBigImage()) {
            return;
        }

        if (null !== $this->getImage()){
            $this->getImage()->move($this->getUploadRootDir(), $this->image_path);
            if (isset($this->temp_image)) {
                // delete the old image
                unlink($this->getUploadRootDir().'/'.$this->temp_image);
                // clear the temp image path
                $this->temp_image = null;
            }
            $this->image = null;
        }
        if (null !== $this->big_image){
            $this->getBigImage()->move($this->getUploadRootDir(), $this->big_image_path);
            if (isset($this->temp_big_image)) {
                // delete the old image
                unlink($this->getUploadRootDir().'/'.$this->temp_big_image);
                // clear the temp image path
                $this->temp_big_image = null;
            }
            $this->big_image = null;
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($file = $this->getImageAbsolutePath()) {
            if(file_exists($file))
                unlink($file);
        }
        
        if ($file = $this->getBigImageAbsolutePath()) {
            if(file_exists($file))
                unlink($file);
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
     * Set alias
     *
     * @param string $alias
     * @return Catalog
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
     * @return Catalog
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
     * @return Catalog
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
     * @return Catalog
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
    
    public function IsActive(){
        if($this->is_active)
            return true;
        return false;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     * @return Catalog
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    
        return $this;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Catalog
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    
        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Catalog
     */
    public function setRoot($root)
    {
        $this->root = $root;
    
        return $this;
    }

    /**
     * Get root
     *
     * @return integer 
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Catalog
     */
    public function setLevel($level)
    {
        $this->level = $level;
    
        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set parent
     *
     * @param \Catalog\CatalogBundle\Entity\Catalog $parent
     * @return Catalog
     */
    public function setParent(\Catalog\CatalogBundle\Entity\Catalog $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Catalog\CatalogBundle\Entity\Catalog 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Catalog\CatalogBundle\Entity\Catalog $children
     * @return Catalog
     */
    public function addChildren(\Catalog\CatalogBundle\Entity\Catalog $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \Catalog\CatalogBundle\Entity\Catalog $children
     */
    public function removeChildren(\Catalog\CatalogBundle\Entity\Catalog $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    public function __toString() {
        return  str_repeat('-', $this->level * 2 ). $this->getTitle();
    }
    
    /**
     * Set meta
     *
     * @param \Catalog\CatalogBundle\Entity\CatalogMeta $meta
     * @return Catalog
     */
    public function setMeta(\Catalog\CatalogBundle\Entity\CatalogMeta $meta = null)
    {
        $this->meta = $meta;
        $meta->setCatalog($this);
    
        return $this;
    }

    /**
     * Get meta
     *
     * @return \Catalog\CatalogBundle\Entity\CatalogMeta 
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Set anons
     *
     * @param string $anons
     * @return Catalog
     */
    public function setAnons($anons)
    {
        $this->anons = $anons;
    
        return $this;
    }

    /**
     * Get anons
     *
     * @return string 
     */
    public function getAnons()
    {
        return $this->anons;
    }

    /**
     * Set image_path
     *
     * @param string $imagePath
     * @return Catalog
     */
    public function setImagePath($imagePath)
    {
        $this->image_path = $imagePath;
    
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
     * Set image_origin_name
     *
     * @param string $imageOriginName
     * @return Catalog
     */
    public function setImageOriginName($imageOriginName)
    {
        $this->image_origin_name = $imageOriginName;
    
        return $this;
    }

    /**
     * Get image_origin_name
     *
     * @return string 
     */
    public function getImageOriginName()
    {
        return $this->image_origin_name;
    }

    /**
     * Set big_image_path
     *
     * @param string $bigImagePath
     * @return Catalog
     */
    public function setBigImagePath($bigImagePath)
    {
        $this->big_image_path = $bigImagePath;
    
        return $this;
    }

    /**
     * Get big_image_path
     *
     * @return string 
     */
    public function getBigImagePath()
    {
        return $this->big_image_path;
    }

    /**
     * Set big_image_origin_name
     *
     * @param string $bigImageOriginName
     * @return Catalog
     */
    public function setBigImageOriginName($bigImageOriginName)
    {
        $this->big_image_origin_name = $bigImageOriginName;
    
        return $this;
    }

    /**
     * Get big_image_origin_name
     *
     * @return string 
     */
    public function getBigImageOriginName()
    {
        return $this->big_image_origin_name;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $image
     */
    public function setImage(UploadedFile $image = null)
    {
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
    public function getImage()
    {
        return $this->image;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $big_image
     */
    public function setBigImage(UploadedFile $big_image = null)
    {
        $this->big_image = $big_image;
        // check if we have an old image path
        if (isset($this->big_image_path)) {
            // store the old name to delete after the update
            $this->temp_big_image = $this->big_image_path;
            $this->big_image_path = null;
        } else {
            $this->big_image_path = 'initial';
        }
    }
    

    /**
     * Get big_image
     *
     * @return string 
     */
    public function getBigImage()
    {
        return $this->big_image;
    }
    
    public function setDeleteImage($delete){
        if($delete && null === $this->getImage()){
            if ($file = $this->getImageAbsolutePath()) {
                if(file_exists($file))
                    unlink($file);
            }
        }        
    }
    
    public function getDeleteImage(){
        return $this->delete_image;
    }
    
    public function setDeleteBigImage($delete){
        if($delete && null === $this->getBigImage()){
            if ($file = $this->getBigImageAbsolutePath()) {
                if(file_exists($file))
                    unlink($file);
            }
        }
    }
    
    public function getDeleteBigImage(){
        return $this->delete_big_image;
    }

    /**
     * Add parent_related
     *
     * @param \Catalog\CatalogBundle\Entity\Catalog $parentRelated
     * @return Catalog
     */
    public function addParentRelated(\Catalog\CatalogBundle\Entity\Catalog $parentRelated)
    {
        $this->parent_related[] = $parentRelated;
    
        return $this;
    }

    /**
     * Remove parent_related
     *
     * @param \Catalog\CatalogBundle\Entity\Catalog $parentRelated
     */
    public function removeParentRelated(\Catalog\CatalogBundle\Entity\Catalog $parentRelated)
    {
        $this->parent_related->removeElement($parentRelated);
    }

    /**
     * Get parent_related
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParentRelated()
    {
        return $this->parent_related;
    }

    /**
     * Add related
     *
     * @param \Catalog\CatalogBundle\Entity\Catalog $related
     * @return Catalog
     */
    public function addRelated(\Catalog\CatalogBundle\Entity\Catalog $related)
    {
        $this->related[] = $related;
    
        return $this;
    }

    /**
     * Remove related
     *
     * @param \Catalog\CatalogBundle\Entity\Catalog $related
     */
    public function removeRelated(\Catalog\CatalogBundle\Entity\Catalog $related)
    {
        $this->related->removeElement($related);
    }

    /**
     * Get related
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * Set show_editor_anons
     *
     * @param boolean $showEditorAnons
     * @return Catalog
     */
    public function setShowEditorAnons($showEditorAnons)
    {
        $this->show_editor_anons = $showEditorAnons;
    
        return $this;
    }

    /**
     * Get show_editor_anons
     *
     * @return boolean 
     */
    public function getShowEditorAnons()
    {
        return $this->show_editor_anons;
    }
     
    /**
     * Set show_editor_description
     *
     * @param boolean $showEditorDescription
     * @return Catalog
     */
    public function setShowEditorDescription($showEditorDescription)
    {
        $this->show_editor_description = $showEditorDescription;
    
        return $this;
    }

    /**
     * Get show_editor_description
     *
     * @return boolean 
     */
    public function getShowEditorDescription()
    {
        return $this->show_editor_description;
    }

    /**
     * Add join_catalog
     *
     * @param \Catalog\CatalogBundle\Entity\CatalogProject $joinCatalog
     * @return Catalog
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
    
    public function isRoot(){
        if($this->getLevel() == 0)
            return true;
        return false;
    }

    /**
     * Set content
     *
     * @param \Catalog\ContentBundle\Entity\Content $content
     * @return Catalog
     */
    public function setContent(\Catalog\ContentBundle\Entity\Content $content = null)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return \Catalog\ContentBundle\Entity\Content 
     */
    public function getContent()
    {
        return $this->content;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parent_related = new \Doctrine\Common\Collections\ArrayCollection();
        $this->related = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->join_catalog = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add children
     *
     * @param \Catalog\CatalogBundle\Entity\Catalog $children
     * @return Catalog
     */
    public function addChild(\Catalog\CatalogBundle\Entity\Catalog $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Catalog\CatalogBundle\Entity\Catalog $children
     */
    public function removeChild(\Catalog\CatalogBundle\Entity\Catalog $children)
    {
        $this->children->removeElement($children);
    }
}
