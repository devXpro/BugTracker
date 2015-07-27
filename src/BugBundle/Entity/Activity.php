<?php

namespace BugBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activity
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="BugBundle\Entity\ActivityRepository")
 */
class Activity
{
    const TYPE_CREATE_ISSUE = 1;
    const TYPE_CHANGE_STATUS_ISSUE = 2;
    const TYPE_COMMENT_ISSUE = 3;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var IssueStatus
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\IssueStatus")
     * @ORM\JoinColumn(name="issue_old_status_id", referencedColumnName="id", nullable=true)
     */
    private $oldStatus;


    /**
     * @var IssueStatus
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\IssueStatus")
     * @ORM\JoinColumn(name="issue_new_status_id", referencedColumnName="id", nullable=true)
     */
    private $newStatus;

    /**
     * @var IssueComment
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\IssueComment")
     * @ORM\JoinColumn(name="issue_comment_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $comment;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;


    /**
     * @var Issue
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\Issue")
     * @ORM\JoinColumn(name="issue_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $issue;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notified", type="boolean")
     */
    private $notified = false;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

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
     * @param Issue $issue
     * @return Activity
     */
    public function setIssue(Issue $issue = null)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * Get issue
     *
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Activity
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
     * @ORM\PrePersist()
     * @return $this
     */
    public function setCreatedNow()
    {
        $this->created = new \DateTime('now', new \DateTimeZone('UTC'));

        return $this;
    }


    /**
     * Set oldStatus
     *
     * @param IssueStatus $oldStatus
     * @return Activity
     */
    public function setOldStatus(IssueStatus $oldStatus = null)
    {
        $this->oldStatus = $oldStatus;

        return $this;
    }

    /**
     * Get oldStatus
     *
     * @return IssueStatus
     */
    public function getOldStatus()
    {
        return $this->oldStatus;
    }

    /**
     * Set newStatus
     *
     * @param IssueStatus $newStatus
     * @return Activity
     */
    public function setNewStatus(IssueStatus $newStatus = null)
    {
        $this->newStatus = $newStatus;

        return $this;
    }

    /**
     * Get newStatus
     *
     * @return IssueStatus
     */
    public function getNewStatus()
    {
        return $this->newStatus;
    }

    /**
     * Set comment
     *
     * @param IssueComment $comment
     * @return Activity
     */
    public function setComment(IssueComment $comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return IssueComment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Activity
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
