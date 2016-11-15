<?php

//src/Catalog/OrderBundle/Entity/Goods.php

namespace Catalog\OrderBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Goods
 * @package Shop\ApiBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="wf_order_goods")
 * @ORM\Entity(repositoryClass="Catalog\OrderBundle\Entity\Repository\GoodsRepository")
 */
class Goods{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank(message="Please enter goods id.", groups={"Order"})
     * @ORM\Column(type="integer")
     */
    protected $goods_id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter goods title.", groups={"Order"})
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter goods alias.", groups={"Order"})
     */
    protected $alias;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $article;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Please enter goods price.", groups={"Order"})
     */
    protected $price;

    /**
     * @ORM\Column(type="integer")
     */
    protected $discount;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Please enter goods count.", groups={"Order"})
     */
    protected $count;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image_path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title_image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $alt_image;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="goods")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $order;

    /**
     * Set id
     *
     * @param integer $id
     * @return Goods
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * @return Goods
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
     * @return Goods
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
     * Set article
     *
     * @param string $article
     * @return Goods
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return string 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Goods
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     * @return Goods
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
     * Set count
     *
     * @param integer $count
     * @return Goods
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set image_path
     *
     * @param string $imagePath
     * @return Goods
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
     * Set title_image
     *
     * @param string $titleImage
     * @return Goods
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
     * @return Goods
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
     * Set order
     *
     * @param \Catalog\OrderBundle\Entity\Order $order
     * @return Goods
     */
    public function setOrder(\Catalog\OrderBundle\Entity\Order $order = null)
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

    /**
     * Set goods_id
     *
     * @param integer $goodsId
     * @return Goods
     */
    public function setGoodsId($goodsId)
    {
        $this->goods_id = $goodsId;
    
        return $this;
    }

    /**
     * Get goods_id
     *
     * @return integer 
     */
    public function getGoodsId()
    {
        return $this->goods_id;
    }
}