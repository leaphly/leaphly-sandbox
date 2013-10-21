<?php

namespace Acme\CartBundle\Form\Type;

use Leaphly\CartBundle\Form\Type\CartFormType as BaseCartFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 * @author Giulio De Donato <liuggio@gmail.com>
 * @package Leaphly\CartBundle\Form\Type
 */
class CartFormType extends BaseCartFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('customer')
        ;
    }
}
