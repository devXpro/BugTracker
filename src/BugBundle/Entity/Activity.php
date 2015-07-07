<?php

namespace BugBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activity
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BugBundle\Entity\ActivityRepository")
 */
class Activity
{
    const TYPE_CREATE_ISSUE=1;
    const TYPE_CHANGE_STATUS_ISSUE=2;
    const TYPE_COMMENT_ISSUE=3;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=10000)
     */
    private $message;


    /**
     * @var Issue
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\Issue")
     * @ORM\JoinColumn(name="issue_id", referencedColumnName="id", nullable=true)
     */
    private $issue;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notified", type="boolean")
     */
    private $notified=false;


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
     * Set type
     *
     * @param integer $type
     * @return Activity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Activity
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set notified
     *
     * @param boolean $notified
     * @return Activity
     */
    public function setNotified($notified)
    {
        $this->notified = $notified;

        return $this;
    }

    /**
     * Get notified
     *
     * @return boolean 
     */
    public function getNotified()
    {
        return $this->notified;
    }

    /**
     * Set issue
     *
     * @param \BugBundle\Entity\Issue $issue
     * @return Activity
     */
    public function setIssue(\BugBundle\Entity\Issue $issue = null)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * Get issue
     *
     * @return \BugBundle\Entity\Issue 
     */
    public function getIssue()
    {
        return $this->issue;
    }
}
