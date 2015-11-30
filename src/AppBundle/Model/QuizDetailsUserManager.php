<?php

namespace AppBundle\Model;

use FOS\UserBundle\Model\UserManager;
use ArtesanIO\MoocsyBundle\Event\MoocsyEvents;
use ArtesanIO\MoocsyBundle\Event\ItemsEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;

class QuizDetailsUserManager extends ContainerAware
{
    protected $em;
    protected $class;
    protected $repository;
    protected $form;
    protected $security;
    protected $user;

    public function __construct(EntityManager $em, $class, UserManager $user)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;
        $this->user = $user;

    }

    public function setSecurity(SecurityContext $security)
    {
        $this->security = $security;

        return $this->security;
    }

    public function getSecurity()
    {
        return $this->security;
    }

    public function getUser()
    {
        $user = $this->user->find($this->getSecurity()->getToken()->getUser()->getId());

        return $user;
    }

    public function create()
    {
        $class = $this->getClass();
        return new $class;
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function findOptionsQuestionQuiz($id)
    {
        return $this->repository->findOptionsQuestionQuiz($id);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function getClass()
    {
        return $this->class;
    }

    public function save($model)
    {
        $model->setUsers($this->getUser());
        $this->_save($model);
    }

    protected function _save($model)
    {
        $this->em->persist($model);
        $this->em->flush();
    }

    public function update($model)
    {
        // $this->container->get('event_dispatcher')->dispatch(
        //     MoocsyEvents::MOOCSY_ITEMS_PRE_UPDATE, new ItemsEvent($model)
        // );

        $this->em->flush();
    }

    public function findQuizItemModuleCourse($quiz, $item, $module, $course)
    {
        return $this->repository->findQuizItemModuleCourse($quiz, $item, $module, $course);
    }

    // public function getQuestion($model)
    // {
    //     $quiz = $model->getItemsQuiz();
    //     $questions = $quiz->getQuestions();
    //
    //     $questionQuantity = count($questions);
    //
    //     $questionQualified = $this->findOptionsQuestionQuiz($quiz->getId());
    //
    //     if($questionQuantity != count($questionQualified)){
    //         $questionsQuizQualified = array();
    //
    //         foreach($questionQualified as $option){
    //             $questionsQuizQualified[$option->getQuestions()->getId()] = $option->getQuestions()->getId();
    //         }
    //
    //         $questionsQuiz = array();
    //
    //         foreach($questions as $question){
    //
    //             if(!in_array($question->getId(), $questionsQuizQualified)){
    //                 $questionsQuiz[$question->getId()] = $question;
    //             }
    //         }
    //
    //         return current($questionsQuiz);
    //     }
    //
    //     return null;
    //
    // }

    public function quizResponded($model)
    {
        $quiz = $model->getItemsQuiz();
        $questions = $quiz->getQuestions();

        $questionQuantity = count($questions);

        $questionQualified = count($this->findOptionsQuestionQuiz($quiz->getId()));

        if($questionQuantity === $questionQualified){
            return true;
        }

        return false;
    }

    public function findQuizUser($item, $user)
    {
        return $this->repository->findQuizUser($item->getItemsQuiz()->getId(), $user->getId());
    }

    public function quizQuestions($item, $quizUser)
    {
        $questionsAnswered = [];

        foreach($quizUser as $question){
            $questionsAnswered[$question->getQuestions()->getId()] = $question->getQuestions()->getId();
        }

        $questions = [];

        foreach ($item->getItemsQuiz()->getQuestions() as $key => $question) {

            if(in_array($question->getId(), $questionsAnswered)){
                $item->getItemsQuiz()->removeQuestion($question);
            }else{
                $questions[$question->getId()] = $question;
            }


        }

        $question = null;

        if(current($questions) !=''){
            $question = current($questions);
        }

        return $question;


    }

    public function findQuestionsCourseUser($course, $user)
    {
        return $this->repository->findQuestionsCourseUser($course, $user);
    }

}



?>
