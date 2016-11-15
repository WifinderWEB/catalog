<?php
// src/Catalog/CatalogBundle/Entity/ContentMeta.php

namespace Catalog\ContentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="content_meta")
 * @ORM\Entity(repositoryClass="Catalog\ContentBundle\Entity\Repository\ContentMetaRepository")
 */
class ContentMeta
{

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
     * @ORM\OneToOne(targetEntity="Content", inversedBy="meta")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     */
    protected $content;

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
     * @return ContentMeta
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
     * @return ContentMeta
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
     * @return ContentMeta
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
     * Set more_scripts
     *
     * @param string $moreScripts
     * @return ContentMeta
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
     * Set content
     *
     * @param \Catalog\ContentBundle\Entity\Content $content
     * @return ContentMeta
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
}
