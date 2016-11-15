<?php

namespace Catalog\OrderBundle\Form\Type\Order;

use Admingenerated\CatalogOrderBundle\Form\BaseOrderType\EditType as BaseEditType;
use Symfony\Component\Form\FormBuilderInterface;
use Catalog\OrderBundle\Form\GoodsType;
/**
 * EditType
 */
class EditType extends BaseEditType
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('created');
        $builder->remove('updated');
        $builder->remove('goods');
    }
}
