<?php
namespace Acme\SimplePurchaseProcessBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,
                               array $options)
    {
        $builder->add( 'username', 'text' );
        $builder->add( 'password', 'password' );
    }

    public function getName()
    {
        return 'UserType';
    }
}
