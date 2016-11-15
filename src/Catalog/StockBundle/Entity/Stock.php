<?php

//src/Catalog/StockBundle/Entity/Stock.php

namespace Catalog\StockBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="stock")
 * @ORM\Entity(repositoryClass="Catalog\StockBundle\Entity\Repository\StockRepository")
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank(message="Please enter title.", groups={"Stock"})
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    protected $title;

    /**
     * @Assert\NotBlank(message="Please enter address.", groups={"Stock"})
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $lat;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $lng;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;

    /**
     * @ORM\OneToMany(targetEntity="Catalog\StockBundle\Entity\StockContent", mappedBy="stock", cascade={"remove", "persist"})
     */
    protected $contents;

    /**
     * @ORM\OneToMany(targetEntity="Catalog\OrderBundle\Entity\Order", mappedBy="stock", cascade={"remove", "persist"})
     */
    protected $orders;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contents = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Stock
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
     * Set address
     *
     * @param string $address
     * @return Stock
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set lat
     *
     * @param string $lat
     * @return Stock
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    
        return $this;
    }

    /**
     * Get lat
     *
     * @return string 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     * @return Stock
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    
        return $this;
    }

    /**
     * Get lng
     *
     * @return string 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Stock
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
     * Add contents
     *
     * @param \Catalog\StockBundle\Entity\StockContent $contents
     * @return Stock
     */
    public function addContent(\Catalog\StockBundle\Entity\StockContent $contents)
    {
        $this->contents[] = $contents;
    
        return $this;
    }

    /**
     * Remove contents
     *
     * @param \Catalog\StockBundle\Entity\StockContent $contents
     */
    public function removeContent(\Catalog\StockBundle\Entity\StockContent $contents)
    {
        $this->contents->removeElement($contents);
    }

    /**
     * Get contents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Add orders
     *
     * @param \Catalog\OrderBundle\Entity\Order $orders
     * @return Stock
     */
    public function addOrder(\Catalog\OrderBundle\Entity\Order $orders)
    {
        $this->orders[] = $orders;
    
        return $this;
    }

    /**
     * Remove orders
     *
     * @param \Catalog\OrderBundle\Entity\Order $orders
     */
    public function removeOrder(\Catalog\OrderBundle\Entity\Order $orders)
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

    public function __toString()
    {
        return $this->title . ' (' .$this->address . ')';
    }
}