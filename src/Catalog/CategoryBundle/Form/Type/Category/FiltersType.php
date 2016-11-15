<?php

namespace Catalog\CategoryBundle\Form\Type\Category;

use Admingenerated\CatalogCategoryBundle\Form\BaseCategoryType\FiltersType as BaseFiltersType;
use Catalog\CategoryBundle\Entity\Repository\CategoryRepository;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * FiltersType
 */
class FiltersType extends BaseFiltersType
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder->add('parent', 'entity', array(
            'multiple' => false,
            'em' => 'default',
            'class' => 'CatalogCategoryBundle:Category',
            'required' => false,
            'label' => 'Parent',
            'translation_domain' => 'Admin',
            'query_builder' => function(CategoryRepository $er){
                return $er->getListCategory();
            },
        ));
    }
}
