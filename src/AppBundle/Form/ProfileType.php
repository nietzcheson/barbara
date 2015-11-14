<?php

namespace ArtesanIO\ArtesanusBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ProfileType extends AbstractType
{
    protected $security;

    public function __construct(SecurityContext $security)
    {
        $this->security = $security;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder
                ->add('file')
                ->add('name')
                ->add('email')
                ->add('username')
                ->add('current_password', 'password', array(
                    'label' => 'form.current_password',
                    'translation_domain' => 'FOSUserBundle',
                    'mapped' => false,
                    'constraints' => new UserPassword(),
                ))
            ;

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ArtesanIO\ArtesanusBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'artesanus_profile_type';
    }
}
