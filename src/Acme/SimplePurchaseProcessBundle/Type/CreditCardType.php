<?php

namespace Acme\SimplePurchaseProcessBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreditCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = $i;
        }

        $years = array();
        $today = new \DateTime();
        $thisYear = $today->format('Y');
        for ($y = $thisYear; $y <= $thisYear + 15; $y++) {
            $years[$y] = $y;
        }

        $builder->add('number', 'text');
        $builder->add('expiry_date_month', 'choice', array(
                            'choices'   => $months,
        ));
        $builder->add('expiry_date_year', 'choice', array(
                             'choices'   => $years,
        ));
        $builder->add('cvv', 'text');
        $builder->add('card_holder', 'text');
        $builder->add('terms', 'checkbox');
        $builder->add('pay', 'submit', array('attr' => array('class' => 'btn-pink big'),
                                                 'label' => 'Pay now'));
    }

    public function getName()
    {
        return 'credit_card';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $fixedOptions = array(
                            'data_class' => 'Acme\SimplePurchaseProcessBundle\Entity\CreditCard',
                        );
        $resolver->setDefaults($fixedOptions);
    }
}
