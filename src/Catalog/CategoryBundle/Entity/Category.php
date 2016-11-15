<?php

//src/Catalog/CategoryBundle/Entity/Category.php

namespace Catalog\CategoryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="category")
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="Catalog\CategoryBundle\Entity\Repository\CategoryRepository")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"Category"})
 * @ORM\HasLifecycleCallbacks
 */
class Category
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"Category"})
     * @Assert\Regex(
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.",
     *       groups={"Category"}
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;

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
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent", cascade="remove")
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
     * @ORM\ManyToMany(targetEntity="Catalog\CategoryBundle\Entity\GroupParameters", inversedBy="category")
     * @ORM\JoinTable(name="category_groups",
     *      joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\OneToMany(targetEntity="Catalog\ContentBundle\Entity\Content", mappedBy="category")
     */
    protected $content;

    protected $delete_image = false;

    private $temp_image;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->content = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return  str_repeat('-', $this->level * 2 ). $this->getTitle();
    }

    public function getImageAbsolutePath()
    {
        return null === $this->image_path ? null : $this->getUploadRootDir().'/'.$this->image_path;
    }

    public function getImageWebPath() {
        return null === $this->image_path ? null : '/' . $this->getUploadDir() . '/' . $this->image_path;
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
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->getImage()) {
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
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getImageAbsolutePath()) {
            if (file_exists($file))
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
     * @return Category
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
     * @return Category
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
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return boolean
     */
    public function isDeleteImage()
    {
        return $this->delete_image;
    }

    /**
     * @param boolean $delete_image
     */
    public function setDeleteImage($delete_image)
    {
        $this->delete_image = $delete_image;
    }

    /**
     * @return mixed
     */
    public function getTempImage()
    {
        return $this->temp_image;
    }

    /**
     * @param mixed $temp_image
     */
    public function setTempImage($temp_image)
    {
        $this->temp_image = $temp_image;
    }


    /**
     * Set image_path
     *
     * @param string $imagePath
     * @return Category
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
     * @return Category
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
     * Set is_active
     *
     * @param boolean $isActive
     * @return Category
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
     * Set lft
     *
     * @param integer $lft
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * @param \Catalog\CategoryBundle\Entity\Category $parent
     * @return Category
     */
    public function setParent(\Catalog\CategoryBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Catalog\CategoryBundle\Entity\Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Catalog\CategoryBundle\Entity\Category $children
     * @return Category
     */
    public function addChild(\Catalog\CategoryBundle\Entity\Category $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Catalog\CategoryBundle\Entity\Category $children
     */
    public function removeChild(\Catalog\CategoryBundle\Entity\Category $children)
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

    /**
     * Add groups
     *
     * @param \Catalog\CategoryBundle\Entity\GroupParameters $groups
     * @return Category
     */
    public function addGroup(\Catalog\CategoryBundle\Entity\GroupParameters $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \Catalog\CategoryBundle\Entity\GroupParameters $groups
     */
    public function removeGroup(\Catalog\CategoryBundle\Entity\GroupParameters $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add content
     *
     * @param \Catalog\ContentBundle\Entity\Content $content
     * @return Category
     */
    public function addContent(\Catalog\ContentBundle\Entity\Content $content)
    {
        $this->content[] = $content;

        return $this;
    }

    /**
     * Remove content
     *
     * @param \Catalog\ContentBundle\Entity\Content $content
     */
    public function removeContent(\Catalog\ContentBundle\Entity\Content $content)
    {
        $this->content->removeElement($content);
    }

    /**
     * Get content
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set anons
     *
     * @param string $anons
     * @return Category
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
     * Set show_editor_anons
     *
     * @param boolean $showEditorAnons
     * @return Category
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
}
