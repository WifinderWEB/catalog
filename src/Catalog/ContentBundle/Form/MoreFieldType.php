<?php

namespace Catalog\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MoreFieldType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    protected $data;
    protected $fields;
    protected $formId;

    public function __construct($data) {
        $this->formId = $data->getId();
        $this->fields = $data->getProject()->getFields();
        $this->data = $data->getMoreField() ? $data->getMoreField() : array();
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        foreach($this->fields as $one){
            $type = $one->getField()->getAlias();
            $activeField = $one->getField()->getIsActive();
            
            if($type == 'input' && $one->getIsActive() && $activeField){
                $builder->add($one->getAlias(), 'text', array(
                    'required' => false,
                    'label' => $one->getTitle(),
                    'data' => $this->isNew() ? $one->getDefaultValue() : (array_key_exists($one->getAlias(), $this->data) ? $this->data[$one->getAlias()] : '')
                ));
            }
            if($type == 'textarea' && $one->getIsActive() && $activeField){
                $builder->add($one->getAlias(), 'textarea', array(
                    'required' => false,
                    'label' => $one->getTitle(),
                    'data' => $this->isNew() ? $one->getDefaultValue() : (array_key_exists($one->getAlias(), $this->data) ? $this->data[$one->getAlias()] : '')
                ));
            }
            if($type == 'ckeditor' && $one->getIsActive() && $activeField){
                $builder->add($one->getAlias(), 'ckeditor', array(
                    'required' => false,
                    'label' => $one->getTitle(),
                    'data' => $this->isNew() ? $one->getDefaultValue() : (array_key_exists($one->getAlias(), $this->data) ? $this->data[$one->getAlias()] : '')
                ));
            }
            if($type == 'checkbox' && $one->getIsActive() && $activeField){
                $builder->add($one->getAlias(), 'checkbox', array(
                    'required' => false,
                    'label' => $one->getTitle(),
                    'data' => $this->isNew() ? ($one->getDefaultValue() == 'true' ? true : false) : (array_key_exists($one->getAlias(), $this->data) ? ($this->data[$one->getAlias()] == 'true' ? true : false) : false)
                ));
            }
        }
    }
    
    private function isNew(){
        if(!$this->formId)
            return true;
        return false;
    }

    /**
     * @return string
     */
    public function getName() {
        return 'catalog_contentbundle_morefield';
    }

}
