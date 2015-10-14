<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{


    public function audioAction($course, $module, $item)
    {
        $itemsManager = $this->get('moocsy.items_manager');

        $item = $itemsManager->findOneItemModuleCourse($course, $module, $item);

        if(null === $item){

            $this->get('artesanus.flashers')->add('warning','Es posible que el recurso al que quieres acceder no exista o haya sido cambiado');

            $coursesManager = $this->get('moocsy.courses_manager');
            $course = $coursesManager->findOneBySlug($course);

            return $this->redirect($this->generateUrl('moocsy_front_course', array('course' => $course->getSlug())));

        }

        $module = $item->getModules();
        $course = $module->getCourses();

        return $this->render('MoocsyBundle:Front:items-audio.html.twig', array(
            'course'        => $course,
            'module'        => $module,
            'item'          => $item,
        ));
    }

    public function audioDownAction($course, $module, $item)
    {
        $itemsManager = $this->get('moocsy.items_manager');

        $item = $itemsManager->findOneItemModuleCourse($course, $module, $item);

        if(null === $item){

            $this->get('artesanus.flashers')->add('warning','Es posible que el recurso al que quieres acceder no exista o haya sido cambiado');

            $coursesManager = $this->get('moocsy.courses_manager');
            $course = $coursesManager->findOneBySlug($course);

            return $this->redirect($this->generateUrl('moocsy_front_course', array('course' => $course->getSlug())));

        }

        $module = $item->getModules();
        $course = $module->getCourses();

        return $this->render('MoocsyBundle:Front:items-audio-download.html.twig', array(
            'course'            => $course,
            'module'            => $module,
            'item'              => $item,
        ));
    }

    public function fileAction($course, $module, $item)
    {
        $itemsManager = $this->get('moocsy.items_manager');

        $item = $itemsManager->findOneItemModuleCourse($course, $module, $item);

        if(null === $item){

            $this->get('artesanus.flashers')->add('warning','Es posible que el recurso al que quieres acceder no exista o haya sido cambiado');

            $coursesManager = $this->get('moocsy.courses_manager');
            $course = $coursesManager->findOneBySlug($course);

            return $this->redirect($this->generateUrl('moocsy_front_course', array('course' => $course->getSlug())));

        }

        $module = $item->getModules();
        $course = $module->getCourses();

        return $this->render('MoocsyBundle:Front:items-file.html.twig', array(
            'course'            => $course,
            'module'            => $module,
            'item'              => $item,
        ));
    }

    public function forumAction($course, $module, $item, Request $request)
    {
        $itemsManager = $this->get('moocsy.items_manager');

        $item = $itemsManager->findOneItemModuleCourse($course, $module, $item);

        if(null === $item){

            $this->get('artesanus.flashers')->add('warning','Es posible que el recurso al que quieres acceder no exista o haya sido cambiado');

            $coursesManager = $this->get('moocsy.courses_manager');
            $course = $coursesManager->findOneBySlug($course);

            return $this->redirect($this->generateUrl('moocsy_front_course', array('course' => $course->getSlug())));

        }

        $module = $item->getModules();
        $course = $module->getCourses();

        $commentsForumManager = $this->get('moocsy.items_forum_comments_manager');

        $commentsForum = $commentsForumManager->create();

        $itemForumCommentsForm = $this->createForm('moocsy_items_forum_comments_type', $commentsForum)->handleRequest($request);

        if($itemForumCommentsForm->isValid()){

            $user = $this->get('artesanus.user_manager')->find($this->getUser());

            $commentsForum->setUsuarios($user);
            $commentsForum->setItemsForum($item->getItemsForum());

            $commentsForumManager->save($commentsForum);

            $this->get('artesanus.flashers')->add('info','Comentario agregado');

            return $this->redirect($this->generateUrl('moocsy_front_items_forum', array(
                'course'            => $course->getSlug(),
                'module'            => $module->getSlug(),
                'item'              => $item->getSlug(),
            )));

        }

        return $this->render('MoocsyBundle:Front:items-forum.html.twig', array(
            'course'            => $course,
            'module'            => $module,
            'item'              => $item,
            'items_comments'    => $itemForumCommentsForm->createView()
        ));
    }

    public function quizAction($course, $module, $item, Request $request)
    {
        $itemsManager = $this->get('moocsy.items_manager');

        $item = $itemsManager->findOneItemModuleCourse($course, $module, $item);

        if(null === $item){

            $this->get('artesanus.flashers')->add('warning','Es posible que el recurso al que quieres acceder no exista o haya sido cambiado');

            $coursesManager = $this->get('moocsy.courses_manager');
            $course = $coursesManager->findOneBySlug($course);

            return $this->redirect($this->generateUrl('moocsy_front_course', array('course' => $course->getSlug())));

        }

        $module = $item->getModules();
        $course = $module->getCourses();

        $quizDetailsUserManager = $this->get('barbara.quiz_details_manager');
        $quizDetails = $quizDetailsUserManager->create();

        $quizUser = $quizDetailsUserManager->findQuizUser($item, $this->getUser());

        $quizQuestions = $quizDetailsUserManager->quizQuestions($item, $quizUser);

        $questionLabel = '';
        $questionId = 0;

        $labelQuestion = '';

        if($quizQuestions != null){
            $labelQuestion = $quizQuestions->getQuestion();
        }

        $quizDetailsForm = $this->createForm('barbara_quiz_details_user_type', $quizDetails, ['question_label' => $labelQuestion])->handleRequest($request);

        if($quizDetailsForm->isValid()){

            $formData = $quizDetailsForm->getData();

            $quizDetails->setUsers($this->getUser());
            $quizDetails->setQuizs($item->getItemsQuiz());
            $quizDetails->setQuestions($quizQuestions);
            $quizDetailsUserManager->save($quizDetails);

            return $this->redirect($this->generateUrl('moocsy_front_items_quiz', array(
                'course'            => $course->getSlug(),
                'module'            => $module->getSlug(),
                'item'              => $item->getSlug(),
            )));

        }

        return $this->render('MoocsyBundle:Front:items-quiz.html.twig', array(
            'course'            => $course,
            'module'            => $module,
            'item'              => $item,
            'quiz_details_form' => $quizDetailsForm->createView(),
            'quiz_question'     => $quizQuestions,
            'quiz_user'         => $quizUser
        ));
    }

    public function videoAction($course, $module, $item)
    {
        $itemsManager = $this->get('moocsy.items_manager');

        $item = $itemsManager->findOneItemModuleCourse($course, $module, $item);

        if(null === $item){

            $this->get('artesanus.flashers')->add('warning','Es posible que el recurso al que quieres acceder no exista o haya sido cambiado');

            $coursesManager = $this->get('moocsy.courses_manager');
            $course = $coursesManager->findOneBySlug($course);

            return $this->redirect($this->generateUrl('moocsy_front_course', array('course' => $course->getSlug())));

        }

        $module = $item->getModules();
        $course = $module->getCourses();

        return $this->render('MoocsyBundle:Front:items-video.html.twig', array(
            'course'            => $course,
            'module'            => $module,
            'item'              => $item,
        ));
    }


}
