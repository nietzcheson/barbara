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
            ->add('value', 'textarea')
            // ->add('options', 'entity', array(
            //     'class' => 'MoocsyBundle:Options',
            //     'query_builder' => function(EntityRepository $er) use($options){
            //         return $er->createQueryBuilder('o')
            //                ->where('o.questions = :question')
            //                ->setParameter('question', $options['question_id'])
            //         ;
            //     },
            //     'property' => 'options',
            //     'expanded' => true,
            //     'label' => $options['question_label']
            // ))
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
        $resolver->setRequired(['question_id']);

        $resolver->setAllowedTypes([
            'question_label' => 'string',
            'question_id' => 'int',
        ]);
    }

    public function getName()
    {
        return 'barbara_quiz_details_user_type';
    }
}
