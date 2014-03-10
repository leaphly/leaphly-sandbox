<?php

namespace Acme\Product\ConferenceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TicketItemType extends AbstractType
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
        parent::buildForm($builder, $options);
        $builder
            ->add('product', 'hidden', array(
                'attr' => array('class'=>'form-control select'),
                'data' =>  $this->product_id,
                'mapped'    => false
            ))
            ->add('conferenceName', 'hidden')
            ->add('when', 'date', array(
                'widget'    => 'single_text',
                'data'      => new \Datetime('10-Oct-2039'),
                'attr'      => array('class' => 'form-control')
            ))
            ->add('position', 'choice', array(
                    'choices'   => array('A' => 'Arena', 'P' => 'Proscenium'),
                    'required'  => true,
                    'label' => 'Position',
                     'attr' => array('class'=>'form-control select')
                ))
            ->add('adults', 'choice', array(
                    'choices'   => array('1' => '1', '2' => '2', '3' => '3'),
                    'required'  => true,
                    'label' => 'Adults',
                'attr' => array('class'=>'form-control select')
                ))
            ->add('children', 'choice', array(
                    'choices'   => array('1' => '1', '2' => '2'),
                    'required'  => false,
                    'label' => 'Children',
                    'attr' => array('class'=>'form-control select')
                ))
             ->add('ticket_save', 'submit', array('label' => ' Add', 'attr' => array('class' => 'btn glyphicon glyphicon-shopping-cart btn btn-inverse btn-lg btn-block')));
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
