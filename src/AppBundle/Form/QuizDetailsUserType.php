<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use ArtesanIO\MoocsyBundle\Form\EventListener\QuizDetailsUserSubscriber;


class QuizDetailsUserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', 'textarea', array(
                'label' => $options['question_label']
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\QuizDetailsUser'
        ));

        $resolver->setRequired(['question_label']);

        $resolver->setAllowedTypes([
            'question_label' => 'string',
        ]);
    }

    public function getName()
    {
        return 'barbara_quiz_details_user_type';
    }
}
