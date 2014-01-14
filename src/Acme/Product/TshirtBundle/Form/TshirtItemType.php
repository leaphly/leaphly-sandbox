<?php

namespace Acme\Product\TshirtBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TshirtItemType extends AbstractType
{

    protected $product_id;

    public function __construct($product_id = null)
    {
        $this->product_id = $product_id;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', 'hidden', array(
                'attr' => array('class'=>'form-control select'),
                'data' =>  $this->product_id,
                'mapped'    => false
                ))
            ->add('color', 'choice', array(
                'choices'   => array('blue' => 'blue', 'white' => 'white', 'yellow' => 'yellow'),
                'required'  => true,
                'label' => 'Color',
                'attr' => array('class'=>'form-control select')
            ))
            ->add('sku', 'choice', array(
                    'choices'   => array('S' => 'Small', 'L' => 'Large'),
                    'required'  => true,
                    'label' => 'Select',
                    'attr' => array('class'=>'form-control select')
                ))
            ->add('quantity', 'choice', array(
                'choices'   => array('1' => '1', '2' => '2', '3' => '3'),
                'required'  => true,
                'label' => 'Quantity',
                'attr' => array('class'=>'form-control select')
            ))
            ->add('tshirt_save', 'submit', array('label' => ' Add', 'attr' => array('class' => 'btn glyphicon glyphicon-shopping-cart btn btn-inverse btn-lg btn-block')))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}
