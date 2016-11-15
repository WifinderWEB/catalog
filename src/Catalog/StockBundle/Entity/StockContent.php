<?php

//src/Catalog/StockBundle/Entity/StockContent.php

namespace Catalog\StockBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="stock_content")
 * @ORM\Entity(repositoryClass="Catalog\StockBundle\Entity\Repository\StockContentRepository")
 */
class StockContent
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Catalog\ContentBundle\Entity\ContentSale", inversedBy="stocks")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $content;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Catalog\StockBundle\Entity\Stock", inversedBy="contents")
     * @ORM\JoinColumn(name="stock_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $stock;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $quantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $reserved;

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return StockContent
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
     * Set reserved
     *
     * @param integer $reserved
     * @return StockContent
     */
    public function setReserved($reserved)
    {
        $this->reserved = $reserved;
    
        return $this;
    }

    /**
     * Get reserved
     *
     * @return integer 
     */
    public function getReserved()
    {
        return $this->reserved;
    }

    /**
     * Set content
     *
     * @param \Catalog\ContentBundle\Entity\ContentSale $content
     * @return StockContent
     */
    public function setContent(\Catalog\ContentBundle\Entity\ContentSale $content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return \Catalog\ContentBundle\Entity\ContentSale 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set stock
     *
     * @param \Catalog\StockBundle\Entity\Stock $stock
     * @return StockContent
     */
    public function setStock(\Catalog\StockBundle\Entity\Stock $stock)
    {
        $this->stock = $stock;
    
        return $this;
    }

    /**
     * Get stock
     *
     * @return \Catalog\StockBundle\Entity\Stock 
     */
    public function getStock()
    {
        return $this->stock;
    }
}