<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserNotifications
 *
 * @ORM\Table(name="moocsy_users_notifications")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserNotificationsRepository")
 */
class UserNotifications
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="notifications", type="text")
     */
    private $notifications;

    /**
     * @ORM\ManyToOne(targetEntity="ArtesanIO\ArtesanusBundle\Entity\User", inversedBy="userNotifications")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */

    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="ArtesanIO\ArtesanusBundle\Entity\User", inversedBy="userNotifications")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */

    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="ArtesanIO\MoocsyBundle\Entity\Courses", inversedBy="userNotifications")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */

    private $courses;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var boolean
     *
     * @ORM\Column(name="viewed", type="boolean")
     */
    private $viewed;

    public function __construct()
    {
        $this->created = new \Datetime('now');
        $this->viewed = 0;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set notifications
     *
     * @param string $notifications
     * @return UserNotifications
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;

        return $this;
    }

    /**
     * Get notifications
     *
     * @return string 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return UserNotifications
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set viewed
     *
     * @param boolean $viewed
     * @return UserNotifications
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;

        return $this;
    }

    /**
     * Get viewed
     *
     * @return boolean 
     */
    public function getViewed()
    {
        return $this->viewed;
    }

    /**
     * Set creator
     *
     * @param \ArtesanIO\ArtesanusBundle\Entity\User $creator
     * @return UserNotifications
     */
    public function setCreator(\ArtesanIO\ArtesanusBundle\Entity\User $creator = null)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \ArtesanIO\ArtesanusBundle\Entity\User 
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set users
     *
     * @param \ArtesanIO\ArtesanusBundle\Entity\User $users
     * @return UserNotifications
     */
    public function setUsers(\ArtesanIO\ArtesanusBundle\Entity\User $users = null)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return \ArtesanIO\ArtesanusBundle\Entity\User 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set courses
     *
     * @param \ArtesanIO\MoocsyBundle\Entity\Courses $courses
     * @return UserNotifications
     */
    public function setCourses(\ArtesanIO\MoocsyBundle\Entity\Courses $courses = null)
    {
        $this->courses = $courses;

        return $this;
    }

    /**
     * Get courses
     *
     * @return \ArtesanIO\MoocsyBundle\Entity\Courses 
     */
    public function getCourses()
    {
        return $this->courses;
    }
}
