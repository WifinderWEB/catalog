<?php

//src/Catalog/ContentBundle/Entity/Category.php

namespace Catalog\ContentBundle\Entity;


class Category {
    protected $category;
    protected $parameters;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parameters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set category
     *
     * @param \Catalog\CategoryBundle\Entity\Category $category
     * @return Content
     */
    public function setCategory(\Catalog\CategoryBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Catalog\CategoryBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function getParameters(){
        return $this->parameters;
    }
}