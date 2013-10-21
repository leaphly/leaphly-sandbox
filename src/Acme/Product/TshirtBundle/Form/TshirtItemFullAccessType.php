<?php

namespace Acme\Product\TshirtBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TshirtItemFullAccessType extends TshirtItemType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('name','text')
            ->add('currency','currency')
            ->add('price', 'number',array(
                'precision' => 2
            ))
            ->add('finalPrice', 'number',array(
                'precision' => 2
            ))
            ->add('createdAt','datetime')
            ->add('updatedAt','datetime')
            ;
    }
}
