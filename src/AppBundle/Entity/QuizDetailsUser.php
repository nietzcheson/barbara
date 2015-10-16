<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuizDetailsUser
 *
 * @ORM\Table(name="barbara_moocsy_quiz_details")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\QuizDetailsUserRepository")
 */
class QuizDetailsUser
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
    * @ORM\ManyToOne(targetEntity="ArtesanIO\MoocsyBundle\Entity\ItemsQuiz", inversedBy="quizDetailsUser")
    * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
    */
    private $quizs;

    /**
    * @ORM\ManyToOne(targetEntity="ArtesanIO\MoocsyBundle\Entity\Questions", inversedBy="quizDetailsUser")
    * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
    */
    private $questions;

    // /**
    // * @ORM\ManyToOne(targetEntity="ArtesanIO\MoocsyBundle\Entity\Options", inversedBy="quizDetailsUser")
    // * @ORM\JoinColumn(name="option_id", referencedColumnName="id")
    // */
    // private $options;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
    * @ORM\ManyToOne(targetEntity="ArtesanIO\ArtesanusBundle\Entity\User", inversedBy="quizDetailsUser")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
    */
    private $users;

    /**
     * @var datetime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    public function __construct()
    {
        $this->created = new \Datetime('now');
        //$this->updated = new \Datetime('now');
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
     * Set itemsQuiz
     *
     * @param integer $itemsQuiz
     * @return QuizDetailsUser
     */
    public function setItemsQuiz($itemsQuiz)
    {
        $this->itemsQuiz = $itemsQuiz;

        return $this;
    }

    /**
     * Get itemsQuiz
     *
     * @return integer
     */
    public function getItemsQuiz()
    {
        return $this->itemsQuiz;
    }

    /**
     * Set questions
     *
     * @param integer $questions
     * @return QuizDetailsUser
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;

        return $this;
    }

    /**
     * Get questions
     *
     * @return integer
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set options
     *
     * @param integer $options
     * @return QuizDetailsUser
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get options
     *
     * @return integer
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set value
     *
     * @param boolean $value
     * @return QuizDetailsUser
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return boolean
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set users
     *
     * @param \ArtesanIO\ArtesanusBundle\Entity\User $users
     * @return QuizDetailsUser
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
     * Set quizs
     *
     * @param \ArtesanIO\MoocsyBundle\Entity\ItemsQuiz $quizs
     * @return QuizDetailsUser
     */
    public function setQuizs(\ArtesanIO\MoocsyBundle\Entity\ItemsQuiz $quizs = null)
    {
        $this->quizs = $quizs;

        return $this;
    }

    /**
     * Get quizs
     *
     * @return \ArtesanIO\MoocsyBundle\Entity\ItemsQuiz
     */
    public function getQuizs()
    {
        return $this->quizs;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return QuizDetailsUser
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
}
