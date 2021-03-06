<?php

//src/Catalog/ContentBundle/Entity/Content.php

namespace Catalog\ContentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * @ORM\Table(name="content")
 * @ORM\Entity(repositoryClass="Catalog\ContentBundle\Entity\Repository\ContentRepository")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"Content"})
 * @ORM\HasLifecycleCallbacks
 */
class Content {

    protected $em;
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
     * @Assert\NotBlank(message="Please enter alias.", groups={"Content"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"Content"}
     * )
     */
    protected $alias;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title_image;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $alt_image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $show_editor_content = true;

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
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $title_big_image;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $alt_big_image;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;

    /**
     * @ORM\OneToOne(targetEntity="ContentMeta", mappedBy="content", cascade={"persist", "remove"})
     */
    protected $meta;

    /**
     * @ORM\OneToOne(targetEntity="ContentSale",
     *   mappedBy="content",
     *   cascade={"persist", "remove"})
     */
    protected $sale;

    /**
     * @ORM\ManyToMany(targetEntity="Catalog\ImageGalleryBundle\Entity\ImageCategory", inversedBy="join_content")
     * @ORM\JoinTable(name="image_gallery_content",
     *      joinColumns={@ORM\JoinColumn(name="content_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_gallery_id", referencedColumnName="id")}
     * )
     */
    protected $image_gallery;

    /**
     * @ORM\ManyToMany(targetEntity="Catalog\VideoGalleryBundle\Entity\VideoCategory", inversedBy="join_content")
     * @ORM\JoinTable(name="video_gallery_content",
     *      joinColumns={@ORM\JoinColumn(name="content_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="video_gallery_id", referencedColumnName="id")}
     * )
     */
    protected $video_gallery;

    /**
     * @ORM\OneToMany(targetEntity="Catalog\CatalogBundle\Entity\Catalog", mappedBy="content", cascade={"remove", "persist"})
     */
    protected $catalogs;
    
    /**
     * @ORM\ManyToMany(targetEntity="Catalog\TagBundle\Entity\Tag", inversedBy="contents")
     * @ORM\JoinTable(name="tag_content",
     *      joinColumns={@ORM\JoinColumn(name="content_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     */
    protected $tags;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Catalog\ProjectBundle\Entity\Project", 
     *   inversedBy="contents")
     * @ORM\JoinColumn(
     *   name="project_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $project;

    /**
     * @ORM\ManyToMany(targetEntity="Catalog\FileGalleryBundle\Entity\File", inversedBy="contents")
     * @ORM\JoinTable(name="file_content",
     *      joinColumns={@ORM\JoinColumn(name="content_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id")}
     * )
     */
    protected $files;
    protected $delete_image = false;
    protected $delete_big_image = false;
    private $temp_image;
    private $temp_big_image;
    protected $more;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $more_field;

    /**
     * @ORM\ManyToMany(targetEntity="Catalog\ContentBundle\Entity\Content", mappedBy="related")
     */
    protected $parent_related;

    /**
     * @ORM\ManyToMany(targetEntity="Catalog\ContentBundle\Entity\Content", inversedBy="parent_related")
     * @ORM\JoinTable(name="related_content",
     *      joinColumns={@ORM\JoinColumn(name="content_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="related_id", referencedColumnName="id")}
     * )
     */
    protected $related;

    /**
     * @ORM\ManyToOne(targetEntity="Catalog\CategoryBundle\Entity\Category", inversedBy="content")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $category;

    protected $group_parameters;

    /**
     * @ORM\ManyToMany(targetEntity="Catalog\CategoryBundle\Entity\Parameter", inversedBy="content")
     * @ORM\JoinTable(name="content_parameter",
     *      joinColumns={@ORM\JoinColumn(name="content_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="parameter_id", referencedColumnName="id")}
     * )
     */
    protected $parameters;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->image_gallery = new \Doctrine\Common\Collections\ArrayCollection();
        $this->video_gallery = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->catalogs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parent_related = new \Doctrine\Common\Collections\ArrayCollection();
        $this->related = new \Doctrine\Common\Collections\ArrayCollection();
        $this->group_parameters = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parameters = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __clone() {
        $this->id = null;
        
        if($this->getTags()){
            $tags = $this->getTags();
            $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($tags as $tag){
                $this->addTag($tag);
            }
        }
        
        if($this->getImageGallery()){
            $imageGallery = $this->getImageGallery();
            $this->image_gallery = new \Doctrine\Common\Collections\ArrayCollection();
            foreach($imageGallery as $one){
                $this->addImageGallery($one);
            }
        }
        
        if($this->getVideoGallery()){
            $videoGallery = $this->getVideoGallery();
            $this->video_gallery = new \Doctrine\Common\Collections\ArrayCollection();
            foreach($videoGallery as $one){
                $this->addVideoGallery($one);
            }
        }
        
        if($this->getFiles()){
            $files = $this->getFiles();
            $this->files =  new \Doctrine\Common\Collections\ArrayCollection();
            foreach($files as $one){
                $this->addFile($one);
            }
        }
        
        if($this->getBigImagePath()){
            $source = $this->getBigImageAbsolutePath();
            $path_info = pathinfo($source);
            $extention = $path_info['extension'];

            $bigImagePath = sha1(uniqid(mt_rand(), true)) . '.' . $extention;
            $this->setBigImagePath($bigImagePath);

            copy($source, $this->getBigImageAbsolutePath());
        }
        
        if($this->getImagePath()){
            $source = $this->getImageAbsolutePath();

            $path_info = pathinfo($source);
            $extention = $path_info['extension'];

            $bigImagePath = sha1(uniqid(mt_rand(), true)) . '.' . $extention;
            $this->setImagePath($bigImagePath);

            copy($source, $this->getImageAbsolutePath());
        }
    }
    
    public function __toString() {
        return '('.$this->id.') - '. $this->title ;
    }

    public function getTitleWithArticle(){
            return '<b>['. $this->getArticle() .']</b> - ' . $this->title;
    }

    public function getImageAbsolutePath() {
        return null === $this->image_path ? null : $this->getUploadRootDir() . '/' . $this->image_path;
    }

    public function getImageWebPath() {
        return null === $this->image_path ? null : '/' . $this->getUploadDir() . '/' . $this->image_path;
    }

    public function getBigImageAbsolutePath() {
        return null === $this->big_image_path ? null : $this->getUploadRootDir() . '/' . $this->big_image_path;
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
            $this->image_path = $filename . '.' . $this->getImage()->guessExtension();
            $this->image_origin_name = $this->getImage()->getClientOriginalName();
        }
        if (null !== $this->getBigImage()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->big_image_path = $filename . '.' . $this->getBigImage()->guessExtension();
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

        if (null !== $this->getImage()) {
            $this->getImage()->move($this->getUploadRootDir(), $this->image_path);
            if (isset($this->temp_image)) {
                // delete the old image
                if(file_exists($this->getUploadRootDir() . '/' . $this->temp_image))
                    unlink($this->getUploadRootDir() . '/' . $this->temp_image);
                // clear the temp image path
                $this->temp_image = null;
            }
            $this->image = null;
        }
        if (null !== $this->big_image) {
            $this->getBigImage()->move($this->getUploadRootDir(), $this->big_image_path);
            if (isset($this->temp_big_image)) {
                // delete the old image
                if(file_exists($this->getUploadRootDir() . '/' . $this->temp_big_image))
                    unlink($this->getUploadRootDir() . '/' . $this->temp_big_image);
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
            if (file_exists($file))
                unlink($file);
        }

        if ($file = $this->getBigImageAbsolutePath()) {
            if (file_exists($file))
                unlink($file);
        }
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Content
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return Content
     */
    public function setAlias($alias) {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias() {
        return $this->alias;
    }

    /**
     * Set anons
     *
     * @param string $anons
     * @return Content
     */
    public function setAnons($anons) {
        $this->anons = $anons;

        return $this;
    }

    /**
     * Get anons
     *
     * @return string 
     */
    public function getAnons() {
        return $this->anons;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Content
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Content
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
     * Add image_gallery
     *
     * @param \Catalog\ImageGalleryBundle\Entity\ImageCategory $imageGallery
     * @return Content
     */
    public function addImageGallery(\Catalog\ImageGalleryBundle\Entity\ImageCategory $imageGallery) {
        $this->image_gallery[] = $imageGallery;

        return $this;
    }

    /**
     * Remove image_gallery
     *
     * @param \Catalog\ImageGalleryBundle\Entity\ImageCategory $imageGallery
     */
    public function removeImageGallery(\Catalog\ImageGalleryBundle\Entity\ImageCategory $imageGallery) {
        $this->image_gallery->removeElement($imageGallery);
    }

    /**
     * Get image_gallery
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImageGallery() {
        return $this->image_gallery;
    }

    /**
     * Add video_gallery
     *
     * @param \Catalog\VideoGalleryBundle\Entity\VideoCategory $videoGallery
     * @return Content
     */
    public function addVideoGallery(\Catalog\VideoGalleryBundle\Entity\VideoCategory $videoGallery) {
        $this->video_gallery[] = $videoGallery;

        return $this;
    }

    /**
     * Remove video_gallery
     *
     * @param \Catalog\VideoGalleryBundle\Entity\VideoCategory $videoGallery
     */
    public function removeVideoGallery(\Catalog\VideoGalleryBundle\Entity\VideoCategory $videoGallery) {
        $this->video_gallery->removeElement($videoGallery);
    }

    /**
     * Get video_gallery
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVideoGallery() {
        return $this->video_gallery;
    }

    /**
     * Add tags
     *
     * @param \Catalog\TagBundle\Entity\Tag $tags
     * @return Content
     */
    public function addTag(\Catalog\TagBundle\Entity\Tag $tags) {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \Catalog\TagBundle\Entity\Tag $tags
     */
    public function removeTag(\Catalog\TagBundle\Entity\Tag $tags) {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags() {
        return $this->tags;
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

    /**
     * Sets file.
     *
     * @param UploadedFile $big_image
     */
    public function setBigImage(UploadedFile $big_image = null) {
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
    public function getBigImage() {
        return $this->big_image;
    }

    /**
     * Set image_path
     *
     * @param string $imagePath
     * @return Content
     */
    public function setImagePath($imagePath) {
        $this->image_path = $imagePath;

        return $this;
    }

    /**
     * Get image_path
     *
     * @return string 
     */
    public function getImagePath() {
        return $this->image_path;
    }

    /**
     * Set image_origin_name
     *
     * @param string $imageOriginName
     * @return Content
     */
    public function setImageOriginName($imageOriginName) {
        $this->image_origin_name = $imageOriginName;

        return $this;
    }

    /**
     * Get image_origin_name
     *
     * @return string 
     */
    public function getImageOriginName() {
        return $this->image_origin_name;
    }

    /**
     * Set big_image_path
     *
     * @param string $bigImagePath
     * @return Content
     */
    public function setBigImagePath($bigImagePath) {
        $this->big_image_path = $bigImagePath;

        return $this;
    }

    /**
     * Get big_image_path
     *
     * @return string 
     */
    public function getBigImagePath() {
        return $this->big_image_path;
    }

    /**
     * Set big_image_origin_name
     *
     * @param string $bigImageOriginName
     * @return Content
     */
    public function setBigImageOriginName($bigImageOriginName) {
        $this->big_image_origin_name = $bigImageOriginName;

        return $this;
    }

    /**
     * Get big_image_origin_name
     *
     * @return string 
     */
    public function getBigImageOriginName() {
        return $this->big_image_origin_name;
    }

    public function getDeleteImage() {
        return $this->delete_image;
    }

    public function setDeleteImage($delete) {
        if ($delete && null === $this->getImage()) {
            if ($file = $this->getImageAbsolutePath()) {
                $this->setImagePath(null);
                if (file_exists($file))
                    unlink($file);
            }
        }
    }

    public function setDeleteBigImage($delete) {
        if ($delete && null === $this->getBigImage()) {
            if ($file = $this->getBigImageAbsolutePath()) {
                $this->setBigImagePath(null);
                if (file_exists($file))
                    unlink($file);
            }
        }
    }

    public function getDeleteBigImage() {
        return $this->delete_big_image;
    }

    /**
     * Set project
     *
     * @param \Catalog\ProjectBundle\Entity\Project $project
     * @return Content
     */
    public function setProject(\Catalog\ProjectBundle\Entity\Project $project = null) {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Catalog\ProjectBundle\Entity\Project 
     */
    public function getProject() {
        return $this->project;
    }

    /**
     * Add files
     *
     * @param \Catalog\FileGalleryBundle\Entity\File $files
     * @return Content
     */
    public function addFile(\Catalog\FileGalleryBundle\Entity\File $files) {
        $this->files[] = $files;

        return $this;
    }

    /**
     * Remove files
     *
     * @param \Catalog\FileGalleryBundle\Entity\File $files
     */
    public function removeFile(\Catalog\FileGalleryBundle\Entity\File $files) {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles() {
        return $this->files;
    }

    /**
     * Set more_field
     *
     * @param string $more_field
     * @return Content
     */
    public function setMoreField($more_field) {
        $this->more_field = json_encode($more_field);

        return $this;
    }

    /**
     * Get more_field
     *
     * @return string 
     */
    public function getMoreField() {
        $more = json_decode($this->more_field, true);
        if($more)
            return $more;
        return array();
    }

    public function getMore() {
        return $this->more;
    }

    public function setMore($more_field) {
        $this->setMoreField($more_field);
    }

    public function getArticle(){
        if($more = $this->getMoreField()){
            if($more['article'])
                return $more['article'];
        }
        return null;
    }

    /**
     * Set show_editor_anons
     *
     * @param boolean $showEditorAnons
     * @return Content
     */
    public function setShowEditorAnons($showEditorAnons) {
        $this->show_editor_anons = $showEditorAnons;

        return $this;
    }

    /**
     * Get show_editor_anons
     *
     * @return boolean 
     */
    public function getShowEditorAnons() {
        return $this->show_editor_anons;
    }

    /**
     * Set show_editor_content
     *
     * @param boolean $showEditorContent
     * @return Content
     */
    public function setShowEditorContent($showEditorContent) {
        $this->show_editor_content = $showEditorContent;

        return $this;
    }

    /**
     * Get show_editor_content
     *
     * @return boolean 
     */
    public function getShowEditorContent() {
        return $this->show_editor_content;
    }

//    public function CatalogReserved(ExecutionContextInterface $context) {
//        global $kernel;
//        if($kernel instanceOf \AppCache) 
//            $kernel = $kernel->getKernel();
//        $em = $kernel->getContainer()->get('doctrine')->getManager();
//        
//        foreach($this->catalogs as $one){
//            $content = $em->getRepository('CatalogCatalogBundle:Catalog')->findCatalogWithItContent($one);
//            if($content){
//                foreach($content as $item){
//                    if(($this->getId() && $item->getId() != $this->getId()) || (!$this->getId())){
//                        $context->addViolationAt(
//                            'catalogs',
//                            'Обьекту каталога "%title%" [id = %catalogId%] уже присвоен контент "%contentTitle%" [id = %contentId%]',
//                            array(
//                                '%title%' => $one->getTitle(),
//                                '%catalogId%' => $one->getId(),
//                                '%contentTitle%' => $item->getTitle(),
//                                '%contentId%' => $item->getId()),
//                            null
//                        );
//                    }
//                }
//            }
//        }
//    }


    /**
     * Set title_image
     *
     * @param string $titleImage
     * @return Content
     */
    public function setTitleImage($titleImage)
    {
        $this->title_image = $titleImage;
    
        return $this;
    }

    /**
     * Get title_image
     *
     * @return string 
     */
    public function getTitleImage()
    {
        return $this->title_image;
    }

    /**
     * Set alt_image
     *
     * @param string $altImage
     * @return Content
     */
    public function setAltImage($altImage)
    {
        $this->alt_image = $altImage;
    
        return $this;
    }

    /**
     * Get alt_image
     *
     * @return string 
     */
    public function getAltImage()
    {
        return $this->alt_image;
    }

    /**
     * Set title_big_image
     *
     * @param string $titleBigImage
     * @return Content
     */
    public function setTitleBigImage($titleBigImage)
    {
        $this->title_big_image = $titleBigImage;
    
        return $this;
    }

    /**
     * Get title_big_image
     *
     * @return string 
     */
    public function getTitleBigImage()
    {
        return $this->title_big_image;
    }

    /**
     * Set alt_big_image
     *
     * @param string $altBigImage
     * @return Content
     */
    public function setAltBigImage($altBigImage)
    {
        $this->alt_big_image = $altBigImage;
    
        return $this;
    }

    /**
     * Get alt_big_image
     *
     * @return string 
     */
    public function getAltBigImage()
    {
        return $this->alt_big_image;
    }

    /**
     * Set sale
     *
     * @param \Catalog\ContentBundle\Entity\ContentSale $sale
     * @return Content
     */
    public function setSale(\Catalog\ContentBundle\Entity\ContentSale $sale = null)
    {
        $this->sale = $sale;
        $sale->setContent($this);
    
        return $this;
    }

    /**
     * Get sale
     *
     * @return \Catalog\ContentBundle\Entity\ContentSale 
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * Add catalogs
     *
     * @param \Catalog\CatalogBundle\Entity\Catalog $catalogs
     * @return Content
     */
    public function addCatalog(\Catalog\CatalogBundle\Entity\Catalog $catalogs)
    {
        $this->catalogs[] = $catalogs;
    
        return $this;
    }

    /**
     * Remove catalogs
     *
     * @param \Catalog\CatalogBundle\Entity\Catalog $catalogs
     */
    public function removeCatalog(\Catalog\CatalogBundle\Entity\Catalog $catalogs)
    {
        $this->catalogs->removeElement($catalogs);
    }

    /**
     * Get catalogs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCatalogs()
    {
        return $this->catalogs;
    }

    /**
     * Add orders
     *
     * @param \Catalog\OrderBundle\Entity\OrderContent $orders
     * @return Content
     */
    public function addOrder(\Catalog\OrderBundle\Entity\OrderContent $orders)
    {
        $this->orders[] = $orders;
    
        return $this;
    }

    /**
     * Remove orders
     *
     * @param \Catalog\OrderBundle\Entity\OrderContent $orders
     */
    public function removeOrder(\Catalog\OrderBundle\Entity\OrderContent $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Set meta
     *
     * @param \Catalog\ContentBundle\Entity\ContentMeta $meta
     * @return Content
     */
    public function setMeta(\Catalog\ContentBundle\Entity\ContentMeta $meta = null)
    {
        $this->meta = $meta;
        $meta->setContent($this);

        return $this;
    }

    /**
     * Get meta
     *
     * @return \Catalog\ContentBundle\Entity\ContentMeta 
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Add parent_related
     *
     * @param \Catalog\ContentBundle\Entity\Content $parentRelated
     * @return Content
     */
    public function addParentRelated(\Catalog\ContentBundle\Entity\Content $parentRelated)
    {
        $this->parent_related[] = $parentRelated;

        return $this;
    }

    /**
     * Remove parent_related
     *
     * @param \Catalog\ContentBundle\Entity\Content $parentRelated
     */
    public function removeParentRelated(\Catalog\ContentBundle\Entity\Content $parentRelated)
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
     * @param \Catalog\ContentBundle\Entity\Content $related
     * @return Content
     */
    public function addRelated(\Catalog\ContentBundle\Entity\Content $related)
    {
        $this->related[] = $related;

        return $this;
    }

    /**
     * Remove related
     *
     * @param \Catalog\ContentBundle\Entity\Content $related
     */
    public function removeRelated(\Catalog\ContentBundle\Entity\Content $related)
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
     * Set category
     *
     * @param \Catalog\CategoryBundle\Entity\Category $category
     * @return Content
     */
    public function setCategory(\Catalog\CategoryBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Catalog\CategoryBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
    public function getGroupParameters(){
        return $this->group_parameters;
    }

    public function addGroupParameter($group_parameters)
    {
        $this->group_parameters[] = $group_parameters;

        return $this;
    }

    public function removeGroupParameter($group_parameters)
    {
        $this->group_parameters->removeElement($group_parameters);
    }

    /**
     * Add parameters
     *
     * @param \Catalog\CategoryBundle\Entity\Parameter $parameters
     * @return Content
     */
    public function addParameter(\Catalog\CategoryBundle\Entity\Parameter $parameters)
    {
        $this->parameters[] = $parameters;

        return $this;
    }

    /**
     * Remove parameters
     *
     * @param \Catalog\CategoryBundle\Entity\Parameter $parameters
     */
    public function removeParameter(\Catalog\CategoryBundle\Entity\Parameter $parameters)
    {
        $this->parameters->removeElement($parameters);
    }

    /**
     * Get parameters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Content
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }
}
