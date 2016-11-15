<?php
// src/Catalog/CatalogBundle/Entity/CatalogMeta.php

namespace Catalog\CatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="catalog_meta")
 * @ORM\Entity(repositoryClass="Catalog\CatalogBundle\Entity\Repository\CatalogMetaRepository")
 */
class CatalogMeta {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $meta_title; 
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $meta_keywords;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $meta_description;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $more_scripts;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $in_site_map = true;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $in_robots = true;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $in_breadcrumbs = true;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $in_menu = true;
    
    /**
     * @ORM\OneToOne(targetEntity="Catalog", inversedBy="meta")
     * @ORM\JoinColumn(name="catalog_id", referencedColumnName="id")
     */
    protected $catalog;
    
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
     * Set meta_title
     *
     * @param string $metaTitle
     * @return CatalogMeta
     */
    public function setMetaTitle($metaTitle)
    {
        $this->meta_title = $metaTitle;
    
        return $this;
    }

    /**
     * Get meta_title
     *
     * @return string 
     */
    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    /**
     * Set meta_keywords
     *
     * @param string $metaKeywords
     * @return CatalogMeta
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->meta_keywords = $metaKeywords;
    
        return $this;
    }

    /**
     * Get meta_keywords
     *
     * @return string 
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    /**
     * Set meta_description
     *
     * @param string $metaDescription
     * @return CatalogMeta
     */
    public function setMetaDescription($metaDescription)
    {
        $this->meta_description = $metaDescription;
    
        return $this;
    }

    /**
     * Get meta_description
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * Set content
     *
     * @param \Catalog\CatalogBundle\Entity\Catalog $catalog
     * @return CatalogMeta
     */
    public function setContent(\Catalog\CatalogBundle\Entity\Catalog $catalog = null)
    {
        $this->catalog = $catalog;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return \Catalog\CatalogBundle\Entity\Catalog 
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    /**
     * Set more_scripts
     *
     * @param string $moreScripts
     * @return CatalogMeta
     */
    public function setMoreScripts($moreScripts)
    {
        $this->more_scripts = $moreScripts;
    
        return $this;
    }

    /**
     * Get more_scripts
     *
     * @return string 
     */
    public function getMoreScripts()
    {
        return $this->more_scripts;
    }

    /**
     * Set in_site_map
     *
     * @param boolean $inSiteMap
     * @return CatalogMeta
     */
    public function setInSiteMap($inSiteMap)
    {
        $this->in_site_map = $inSiteMap;
    
        return $this;
    }

    /**
     * Get in_site_map
     *
     * @return boolean 
     */
    public function getInSiteMap()
    {
        return $this->in_site_map;
    }

    /**
     * Set in_robots
     *
     * @param boolean $inRobots
     * @return CatalogMeta
     */
    public function setInRobots($inRobots)
    {
        $this->in_robots = $inRobots;
    
        return $this;
    }

    /**
     * Get in_robots
     *
     * @return boolean 
     */
    public function getInRobots()
    {
        return $this->in_robots;
    }

    /**
     * Set in_breadcrumbs
     *
     * @param boolean $inBreadcrumbs
     * @return CatalogMeta
     */
    public function setInBreadcrumbs($inBreadcrumbs)
    {
        $this->in_breadcrumbs = $inBreadcrumbs;
    
        return $this;
    }

    /**
     * Get in_breadcrumbs
     *
     * @return boolean 
     */
    public function getInBreadcrumbs()
    {
        return $this->in_breadcrumbs;
    }

    /**
     * Set catalog
     *
     * @param \Catalog\CatalogBundle\Entity\Catalog $catalog
     * @return CatalogMeta
     */
    public function setCatalog(\Catalog\CatalogBundle\Entity\Catalog $catalog = null)
    {
        $this->catalog = $catalog;
    
        return $this;
    }

    /**
     * Set in_menu
     *
     * @param boolean $inMenu
     * @return CatalogMeta
     */
    public function setInMenu($inMenu)
    {
        $this->in_menu = $inMenu;
    
        return $this;
    }

    /**
     * Get in_menu
     *
     * @return boolean 
     */
    public function getInMenu()
    {
        return $this->in_menu;
    }
    
    public function isMenu(){
        if($this->getInMenu())
            return true;
        return false;
    }
}