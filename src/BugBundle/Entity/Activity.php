<?php

namespace BugBundle\Entity;

use Doctrine\DBAL\Types\JsonArrayType;
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
     * @var array
     * @ORM\Column(name="vars", type="string", length=10000)
     */
    private $vars;


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
        $this->created = new \DateTime('now');
        return $this;
    }

    /**
     * Set vars
     *
     * @param string $vars
     * @return Activity
     */
    public function setVars($vars)
    {
        $this->vars = json_encode($vars);

        return $this;
    }

    /**
     * Get vars
     *
     * @return string
     */
    public function getVars()
    {
        return json_decode($this->vars);
    }
}
