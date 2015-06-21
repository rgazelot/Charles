<?php

namespace Charles\UserBundle\Form;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('password')
            ->add('phone')
            ->add('address')
            ->add('zip')
            ->add('city')
            ->add('country')
            ->add('gender')
            ->add('via')
            ->add('informations')
            ->add('budget')
            ->add('identifier');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'Charles\\UserBundle\\Entity\\User',
            'csrf_protection'   => false,
        ));
    }

    public function getName()
    {
        return 'user';
    }
}
