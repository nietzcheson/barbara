<?php

namespace ArtesanIO\ACLBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabled')
            ->add('nombre')
            ->add('groups', 'entity', array(
                'class' => 'ACLBundle:Group',
                'property' => 'name',
                'expanded' => true,
                'multiple' => true,
                'required' => true,
                'empty_value' => '--Seleccione un Grupo--',
            ))
        ;
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }


    public function getName()
    {
        return 'artesanio_user_profile';
    }
}
