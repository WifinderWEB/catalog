<?php
// src/Catalog/UserBundle/Entity/User.php

namespace Catalog\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Catalog\ProjectBundle\Entity\Project;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="string", length=225, nullable=true)
     * @Assert\Regex( 
     *       pattern="/^[a-zA-Zа-яА-ЯёЁ]+$/",
     *       message="Field can contain only letters."
     * )
     */
    protected $full_name;
    
    /**
     * @ORM\Column(type="string", length=225, nullable=true)
     */
    protected $phone;
    
    /**
     * @ORM\ManyToMany(targetEntity="Catalog\ProjectBundle\Entity\Project", inversedBy="users")
     * @ORM\JoinTable(name="user_project",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")}
     * )
     */
    protected $projects;
    
    /**
     * @var boolean
     */
    protected $enabled = true;
    
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
     * Set full_name
     *
     * @param string $fullName
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->full_name = $fullName;
    
        return $this;
    }

    /**
     * Get full_name
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getEnabled(){
        return $this->enabled;
    }

    /**
     * Add projects
     *
     * @param \Catalog\ProjectBundle\Entity\Project $projects
     * @return User
     */
    public function addProject(\Catalog\ProjectBundle\Entity\Project $projects)
    {
        $this->projects[] = $projects;
    
        return $this;
    }

    /**
     * Remove projects
     *
     * @param \Catalog\ProjectBundle\Entity\Project $projects
     */
    public function removeProject(\Catalog\ProjectBundle\Entity\Project $projects)
    {
        $this->projects->removeElement($projects);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProjects()
    {
        return $this->projects;
    }
}