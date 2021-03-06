<?php

//src/Catalog/CategoryBundle/Entity/Parameter.php

namespace Catalog\CategoryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="category_parameter")
 * @ORM\Entity(repositoryClass="Catalog\CategoryBundle\Entity\Repository\ParameterRepository")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"Parameter"})
 */
class Parameter
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank(message="Please enter alias.", groups={"Parameter"})
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"Parameter"})
     * @Assert\Regex(
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.",
     *       groups={"Parameter"}
     * )
     */
    protected $alias;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;

    /**
     * @ORM\ManyToOne(targetEntity="GroupParameters", inversedBy="parameter")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $group;

    /**
     * @ORM\ManyToMany(targetEntity="Catalog\ContentBundle\Entity\Content", mappedBy="parameters")
     */
    protected $content;

    public  function __toString()
    {
        return $this->title;
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
     * @return Parameter
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
     * @return Parameter
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
     * Set is_active
     *
     * @param boolean $isActive
     * @return Parameter
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
     * Set group
     *
     * @param \Catalog\CategoryBundle\Entity\GroupParameters $group
     * @return Parameter
     */
    public function setGroup(\Catalog\CategoryBundle\Entity\GroupParameters $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \Catalog\CategoryBundle\Entity\GroupParameters 
     */
    public function getGroup()
    {
        return $this->group;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->catalogs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add content
     *
     * @param \Catalog\ContentBundle\Entity\Content $content
     * @return Parameter
     */
    public function addContent(\Catalog\ContentBundle\Entity\Content $content)
    {
        $this->content[] = $content;

        return $this;
    }

    /**
     * Remove content
     *
     * @param \Catalog\ContentBundle\Entity\Content $content
     */
    public function removeContent(\Catalog\ContentBundle\Entity\Content $content)
    {
        $this->content->removeElement($content);
    }

    /**
     * Get content
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContent()
    {
        return $this->content;
    }
}
