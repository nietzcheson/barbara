<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserNotifications
 *
 * @ORM\Table(name="moocsy_courses_notifications")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CoursesNotificationsRepository")
 */
class CoursesNotifications
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
     * @return CoursesNotifications
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
     * @return CoursesNotifications
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
     * @return CoursesNotifications
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
     * @return CoursesNotifications
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
     * Set courses
     *
     * @param \ArtesanIO\MoocsyBundle\Entity\Courses $courses
     * @return CoursesNotifications
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
