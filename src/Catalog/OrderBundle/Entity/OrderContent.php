<?php

//src/Catalog/OrderBundle/Entity/OrderContent.php

namespace Catalog\OrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="wf_order_content")
 * @ORM\Entity(repositoryClass="Catalog\OrderBundle\Entity\Repository\OrderContentRepository")
 */
class OrderContent
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Catalog\ContentBundle\Entity\Content", inversedBy="orders")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $content;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Catalog\OrderBundle\Entity\Order", inversedBy="contents")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $order;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $quantity;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
    protected $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $discount;

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
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $barcode;

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return OrderContent
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    
        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return OrderContent
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     * @return OrderContent
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
     * Set weight
     *
     * @param integer $weight
     * @return OrderContent
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
     * @return OrderContent
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
     * @return OrderContent
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
     * @return OrderContent
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
     * Set barcode
     *
     * @param string $barcode
     * @return OrderContent
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
     * @return OrderContent
     */
    public function setContent(\Catalog\ContentBundle\Entity\Content $content)
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
     * Set order
     *
     * @param \Catalog\OrderBundle\Entity\Order $order
     * @return OrderContent
     */
    public function setOrder(\Catalog\OrderBundle\Entity\Order $order)
    {
        $this->order = $order;
    
        return $this;
    }

    /**
     * Get order
     *
     * @return \Catalog\OrderBundle\Entity\Order 
     */
    public function getOrder()
    {
        return $this->order;
    }
}