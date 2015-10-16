<?php

namespace AppBundle\Model;

use ArtesanIO\ArtesanusBundle\Model\UserManager;
use ArtesanIO\MoocsyBundle\Event\MoocsyEvents;
use ArtesanIO\MoocsyBundle\Event\ItemsEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;

class CoursesNotificationsManager extends ContainerAware
{
    protected $em;
    protected $class;
    protected $repository;

    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;

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

    public function findNotificationsCourseUser($course, $user)
    {
        return $this->repository->findNotificationsCourseUser($course, $user);
    }

    public function findOneByCourses($course)
    {
        return $this->repository->findBy(array('courses' => $course));
    }

}



?>
