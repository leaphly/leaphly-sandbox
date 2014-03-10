<?php

namespace Acme\CartBundle\Form\Type;

use Leaphly\Cart\Form\Type\CartFormType as BaseCartFormType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 * @author Giulio De Donato <liuggio@gmail.com>
 * @package Leaphly\Cart\Form\Type
 */
class CartFormType extends BaseCartFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('customer')
        ;
    }
}
