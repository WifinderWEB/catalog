<?php

namespace Catalog\CatalogBundle\Form\Type\Catalog;

use Admingenerated\CatalogCatalogBundle\Form\BaseCatalogType\FiltersType as BaseFiltersType;
use Catalog\CatalogBundle\Entity\Repository\CatalogRepository;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * FiltersType
 */
class FiltersType extends BaseFiltersType {

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        
        $builder->add('parent', 'entity', array(
            'multiple' => false,
            'em' => 'default', 
            'class' => 'CatalogCatalogBundle:Catalog',
            'required' => false,
            'label' => 'Parent',
            'translation_domain' => 'Admin',
            'query_builder' => function(CatalogRepository $er){
                return $er->getListCategory();
            },
        ));
    }

}
