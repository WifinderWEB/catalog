<?php

namespace Catalog\CatalogBundle\Form;

use Catalog\CategoryBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CatalogParametersType extends AbstractType
{

    protected $category;
    protected $selected;

    public function __construct($data)
    {
        $this->category = $data->getCategory();
        $this->selected = $data->getParameters();
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        foreach($this->selected as $one){
//            var_dump($one->getId());
//        }
//        var_dump('3 - ' . $this->selected->count());

        foreach($this->category->getGroups() as $group) {
            $options = array(
                'choices' => $group->getParameter(),
                'class' => 'Catalog\CategoryBundle\Entity\Parameter',
                'label' => $group->getTitle(),
                'multiple' => true,
                'em' => 'default',
                'required' => false,
                'attr' => array('class' => 'multiselect'),
//                'data' => $this->selected
            );
//            var_dump($this->selected->count()); exit;
            $p = array();
            if($this->selected->count() > 0){
                foreach($group->getParameter() as $one) {
                    if($this->selected->contains($one))
                        $p[] = $one;
                }

                $options['data'] = $p;
            }
            $builder->add('group_' . $group->getId(), 'entity', $options);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'catalog_catalogbundle_catalogparameter';
    }
}

