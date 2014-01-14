<?php

namespace Acme\Product\ConferenceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TicketItemFullAccessType extends AbstractType
{
     /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // todo: Shaping the FormType on the needs of the admin
        parent::buildForm($builder, $options);
        $builder
            ->add('product')
            ->add('conferenceName')
            ->add('when', 'choice', array(
                    'choices'   => array('1' => '10-Oct-2039', '2' => '11-Oct-2039'),
                    'required'  => true,
                    'label' => 'date'
                ))
            ->add('position', 'choice', array(
                    'choices'   => array('A' => 'Arena', 'P' => 'Proscenium'),
                    'required'  => true,
                    'label' => 'Position'
                ))
            ->add('adults', 'choice', array(
                    'choices'   => array('1' => '1', '2' => '2', '3' => '3'),
                    'required'  => false,
                    'label' => 'adult'
                ))
            ->add('children', 'choice', array(
                    'choices'   => array('1' => '1', '2' => '2'),
                    'required'  => false,
                    'label' => 'children'
                ))
             ->add('save', 'submit', array('label' => ' Add', 'attr' => array('class' => 'btn btn-default glyphicon glyphicon-shopping-cart')));

    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}
