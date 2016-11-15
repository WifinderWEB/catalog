<?php
// src/Catalog/ContentBundle/Entity/ContentSale.php

namespace Catalog\ContentBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="catalog_sale")
 * @ORM\Entity(repositoryClass="Catalog\ContentBundle\Entity\Repository\ContentSaleRepository")
 */
class ContentSale
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2, nullable=true)
     */
    protected $purchase_price;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2, nullable=true)
     */
    protected $retail_price;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $VAT = true;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $weight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $length;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $width;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $height;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $discount;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $barcode;

    /**
     * @ORM\OneToMany(targetEntity="Catalog\StockBundle\Entity\StockContent",
     *     mappedBy="content",
     *     cascade={"remove", "persist"})
     */
    protected $stocks;

    /**
     * @ORM\OneToOne(targetEntity="Content", inversedBy="sale")
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
     * Set purchase_price
     *
     * @param string $purchasePrice
     * @return ContentSale
     */
    public function setPurchasePrice($purchasePrice)
    {
        $this->purchase_price = $purchasePrice;

        return $this;
    }

    /**
     * Get purchase_price
     *
     * @return string
     */
    public function getPurchasePrice()
    {
        return $this->purchase_price;
    }

    /**
     * Set retail_price
     *
     * @param string $retailPrice
     * @return ContentSale
     */
    public function setRetailPrice($retailPrice)
    {
        $this->retail_price = $retailPrice;

        return $this;
    }

    /**
     * Get retail_price
     *
     * @return string
     */
    public function getRetailPrice()
    {
        return $this->retail_price;
    }

    /**
     * Set VAT
     *
     * @param boolean $vAT
     * @return ContentSale
     */
    public function setVAT($vAT)
    {
        $this->VAT = $vAT;

        return $this;
    }

    /**
     * Get VAT
     *
     * @return boolean
     */
    public function getVAT()
    {
        return $this->VAT;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     * @return ContentSale
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set length
     *
     * @param integer $length
     * @return ContentSale
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set width
     *
     * @param integer $width
     * @return ContentSale
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return ContentSale
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     * @return ContentSale
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return integer
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set barcode
     *
     * @param string $barcode
     * @return ContentSale
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;

        return $this;
    }

    /**
     * Get barcode
     *
     * @return string
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * Set content
     *
     * @param \Catalog\ContentBundle\Entity\Content $content
     * @return ContentSale
     */
    public function setCatalog(\Catalog\ContentBundle\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \Catalog\ContentBundle\Entity\Content
     */
    public function getCatalog()
    {
        return $this->content;
    }

    /**
     * Set content
     *
     * @param \Catalog\ContentBundle\Entity\Content $content
     * @return ContentSale
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
        $this->stocks = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add stocks
     *
     * @param \Catalog\StockBundle\Entity\StockContent $stocks
     * @return ContentSale
     */
    public function addStock(\Catalog\StockBundle\Entity\StockContent $stocks)
    {

        $this->stocks[] = $stocks;
        $stocks->setContent($this);
    
        return $this;
    }

    /**
     * Remove stocks
     *
     * @param \Catalog\StockBundle\Entity\StockContent $stocks
     */
    public function removeStock(\Catalog\StockBundle\Entity\StockContent $stocks)
    {
        $this->stocks->removeElement($stocks);
    }

    /**
     * Get stocks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStocks()
    {
        return $this->stocks;
    }

    public function setStocks(\Doctrine\Common\Collections\ArrayCollection $itemsArray = null){
        if(!$itemsArray)
            $this->stocks->clear();
        else
            $this->stocks = $itemsArray;
    }
}