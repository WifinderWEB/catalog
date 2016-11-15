<?php

//src/Catalog/OrderBundle/Entity/Order.php

namespace Catalog\OrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * @ORM\Table(name="wf_order")
 * @ORM\Entity(repositoryClass="Catalog\OrderBundle\Entity\Repository\OrderRepository")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $project_id;
    /**
     * @ORM\ManyToOne(targetEntity="Catalog\ProjectBundle\Entity\Project", inversedBy="join_order")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $project;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter last name.", groups={"Order"})
     * @Assert\Regex(
     *       pattern="/^([a-zA-Zа-яА-ЯёЁ]+(\s+)?)+([a-zA-Zа-яА-ЯёЁ]+)$/u",
     *       message="Field can contain only letters.",
     *       groups={"Order"}
     * )
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter middle name.", groups={"Order"})
     * @Assert\Regex(
     *       pattern="/^([a-zA-Zа-яА-ЯёЁ]+(\s+)?)+([a-zA-Zа-яА-ЯёЁ]+)$/u",
     *       message="Field can contain only letters.",
     *       groups={"Order"}
     * )
     */
    protected $middleName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter first name.", groups={"Order"})
     * @Assert\Regex(
     *       pattern="/^([a-zA-Zа-яА-ЯёЁ]+(\s+)?)+([a-zA-Zа-яА-ЯёЁ]+)$/u",
     *       message="Field can contain only letters.",
     *       groups={"Order"}
     * )
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter country.", groups={"Order"})
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter region.", groups={"Order"})
     */
    protected $region;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter city.", groups={"Order"})
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter street.", groups={"Order"})
     */
    protected $street;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter house.", groups={"Order"})
     */
    protected $house;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $room;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *       pattern="/^[\d]{6}$/",
     *       message="Wrong code.",
     *       groups={"Order"}
     * )
     */
    protected $postcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter phone.", groups={"Order"})
     * @Assert\Regex(
     *       pattern="/^\+([\d]{1})\s\(([\d]{3})\)\s([\d]{3})\-([\d]{4})$/",
     *       message="Wrong phone.",
     *       groups={"Order"}
     * )
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Please enter email.", groups={"Order"})
     * @Assert\Regex(
     *       pattern="/^[-._\+a-zA-Z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/",
     *       message="Wrong email.",
     *       groups={"Order"}
     * )
     */
    protected $email;

    /**
     * @ORM\Column(type="integer")
     */
    protected $discount;

    /**
     * @ORM\Column(type="float")
     */
    protected $itog;

    /**
     * @ORM\OneToMany(targetEntity="Goods", mappedBy="order", cascade={"remove", "persist"})
     */
    protected $goods;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="Catalog\StockBundle\Entity\Stock", inversedBy="orders")
     * @ORM\JoinColumn(name="stock_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $stock;

    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    public function getFullName(){
        $result  = array();
        if($this->lastName)
            $result[] = $this->lastName;
        if($this->firstName)
            $result[] = $this->firstName;
        if($this->middleName)
            $result[] = $this->middleName;
        return implode(' ', $result);
    }

    public function getAddress(){
        $result  = array();
        if($this->getCountry())
            $result[] = $this->getCountry();
        if($this->getRegion())
            $result[] = $this->getRegion();
        if($this->getCity())
            $result[] = $this->getCity();
        if($this->getStreet())
            $result[] = 'ул. ' . $this->getStreet();
        if($this->getHouse())
            $result[] = 'дом № ' . $this->getHouse();
        if($this->getRoom())
            $result[] = 'кв. ' . $this->getRoom();
        if($this->getPostcode())
            $result[] = 'почтовый индекс ' . $this->getPostcode();

        return implode(', ', $result);
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->goods = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set project_id
     *
     * @param integer $projectId
     * @return Order
     */
    public function setProjectId($projectId)
    {
        $this->project_id = $projectId;
    
        return $this;
    }

    /**
     * Get project_id
     *
     * @return integer 
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Order
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     * @return Order
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    
        return $this;
    }

    /**
     * Get middleName
     *
     * @return string 
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Order
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Order
     */
    public function setCountry($country)
    {
        $this->country = $country;
    
        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return Order
     */
    public function setRegion($region)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Order
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Order
     */
    public function setStreet($street)
    {
        $this->street = $street;
    
        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set house
     *
     * @param string $house
     * @return Order
     */
    public function setHouse($house)
    {
        $this->house = $house;
    
        return $this;
    }

    /**
     * Get house
     *
     * @return string 
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * Set room
     *
     * @param string $room
     * @return Order
     */
    public function setRoom($room)
    {
        $this->room = $room;
    
        return $this;
    }

    /**
     * Get room
     *
     * @return string 
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return Order
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    
        return $this;
    }

    /**
     * Get postcode
     *
     * @return string 
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Order
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Order
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     * @return Order
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
     * Set itog
     *
     * @param float $itog
     * @return Order
     */
    public function setItog($itog)
    {
        $this->itog = $itog;
    
        return $this;
    }

    /**
     * Get itog
     *
     * @return float 
     */
    public function getItog()
    {
        return $this->itog;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Order
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
     * Set status
     *
     * @param string $status
     * @return Order
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Order
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

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Order
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set project
     *
     * @param \Catalog\ProjectBundle\Entity\Project $project
     * @return Order
     */
    public function setProject(\Catalog\ProjectBundle\Entity\Project $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \Catalog\ProjectBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add goods
     *
     * @param \Catalog\OrderBundle\Entity\Goods $goods
     * @return Order
     */
    public function addGood(\Catalog\OrderBundle\Entity\Goods $goods)
    {
        $this->goods[] = $goods;
    
        return $this;
    }

    /**
     * Remove goods
     *
     * @param \Catalog\OrderBundle\Entity\Goods $goods
     */
    public function removeGood(\Catalog\OrderBundle\Entity\Goods $goods)
    {
        $this->goods->removeElement($goods);
    }

    /**
     * Get goods
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGoods()
    {
        return $this->goods;
    }

    /**
     * Set stock
     *
     * @param \Catalog\StockBundle\Entity\Stock $stock
     * @return Order
     */
    public function setStock(\Catalog\StockBundle\Entity\Stock $stock = null)
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