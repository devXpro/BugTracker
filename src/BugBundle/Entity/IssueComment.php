<?php

namespace BugBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class IssueComment
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
     * @ORM\Column(name="body", type="string", length=10000)
     */
    private $body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var Issue
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\Issue", inversedBy="comments")
     * @ORM\JoinColumn(name="issue_id", referencedColumnName="id", nullable=true)
     */
    private $issue;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     */
    private $author;

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
     * Set body
     *
     * @param string $body
     * @return Comment
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Comment
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
     * Set issue
     *
     * @param \BugBundle\Entity\Issue $issue
     * @return IssueComment
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
     * Set author
     *
     * @param \BugBundle\Entity\User $author
     * @return IssueComment
     */
    public function setAuthor(\BugBundle\Entity\User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \BugBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
