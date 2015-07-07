<?php

namespace BugBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Issue
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BugBundle\Entity\IssueRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Issue
{
    const TYPE_BUG = 1;
    const TYPE_SUBTASK = 2;
    const TYPE_TASK = 3;
    const TYPE_STORY = 4;

    public function __construct()
    {
        $this->childrenIssues = new ArrayCollection();
        $this->collaborators = new ArrayCollection();
    }
    public function __toString(){
        return $this->code.'-'.$this->id.' '.$this->summary;
    }
    /**
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
     * @ORM\Column(name="summary", type="string", length=1000)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=10)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=10000)
     */
    private $description;

    /**
     * @var Collection | IssueComment[]
     * @ORM\OneToMany(targetEntity="BugBundle\Entity\IssueComment", mappedBy="issue")
     */
    private $comments;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var IssuePriority
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\IssuePriority")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id", nullable=false)
     */
    private $priority;

    /**
     * @var IssueStatus
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\IssueStatus")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id", nullable=false)
     */
    private $status;

    /**
     * @var IssueResolution
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\IssueResolution")
     * @ORM\JoinColumn(name="resolution_id", referencedColumnName="id", nullable=false)
     */
    private $resolution;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id", nullable=false)
     */
    private $reporter;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\User")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id", nullable=false)
     */
    private $assignee;

    /**
     * @var Collection | User[]
     * @ORM\ManyToMany(targetEntity="BugBundle\Entity\User", inversedBy="issues")
     * @ORM\JoinTable(name="collaborators")
     **/
    private $collaborators;

    /**
     * @var Issue
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\Issue", inversedBy="childrenIssues")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private $parentIssue;


    /**
     * @var Collection | Issue[]
     * @ORM\OneToMany(targetEntity="BugBundle\Entity\Issue", mappedBy="parentIssue")
     */
    private $childrenIssues;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\Project", inversedBy="issues")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private $project;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;


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
     * Set summary
     *
     * @param string $summary
     * @return Issue
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Issue
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Issue
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
     * Set created
     *
     * @param \DateTime $created
     * @return Issue
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @return $this
     */
    public function setCreatedNow(){
        $this->created = new \DateTime('now');

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
     * @ORM\PreUpdate()
     *
     */
    public function setUpdatedNow(){
        $this->updated = new \DateTime('now');
    }
    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Issue
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set priority
     *
     * @param \BugBundle\Entity\IssuePriority $priority
     * @return Issue
     */
    public function setPriority(\BugBundle\Entity\IssuePriority $priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return \BugBundle\Entity\IssuePriority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set status
     *
     * @param \BugBundle\Entity\IssueStatus $status
     * @return Issue
     */
    public function setStatus(\BugBundle\Entity\IssueStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \BugBundle\Entity\IssueStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set resolution
     *
     * @param \BugBundle\Entity\IssueResolution $resolution
     * @return Issue
     */
    public function setResolution(\BugBundle\Entity\IssueResolution $resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Get resolution
     *
     * @return \BugBundle\Entity\IssueResolution
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set reporter
     *
     * @param \BugBundle\Entity\User $reporter
     * @return Issue
     */
    public function setReporter(\BugBundle\Entity\User $reporter)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return \BugBundle\Entity\User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set assignee
     *
     * @param \BugBundle\Entity\User $assignee
     * @return Issue
     */
    public function setAssignee(\BugBundle\Entity\User $assignee)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return \BugBundle\Entity\User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * Add collaborators
     *
     * @param \BugBundle\Entity\User $collaborators
     * @return Issue
     */
    public function addCollaborator(\BugBundle\Entity\User $collaborators)
    {
        foreach($this->collaborators as $col){
            if($col==$collaborators)
                return $this;
        }

            $this->collaborators[] = $collaborators;

        return $this;
    }

    /**
     * Remove collaborators
     *
     * @param \BugBundle\Entity\User $collaborators
     */
    public function removeCollaborator(\BugBundle\Entity\User $collaborators)
    {
        $this->collaborators->removeElement($collaborators);
    }

    /**
     * Get collaborators
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * Set parentIssue
     *
     * @param \BugBundle\Entity\Issue $parentIssue
     * @return Issue
     */
    public function setParentIssue(\BugBundle\Entity\Issue $parentIssue)
    {
        $this->parentIssue = $parentIssue;

        return $this;
    }

    /**
     * Get parentIssue
     *
     * @return \BugBundle\Entity\Issue
     */
    public function getParentIssue()
    {
        return $this->parentIssue;
    }

    /**
     * Add childrenIssues
     *
     * @param \BugBundle\Entity\Issue $childrenIssues
     * @return Issue
     */
    public function addChildrenIssue(\BugBundle\Entity\Issue $childrenIssues)
    {
        $this->childrenIssues[] = $childrenIssues;

        return $this;
    }

    /**
     * Remove childrenIssues
     *
     * @param \BugBundle\Entity\Issue $childrenIssues
     */
    public function removeChildrenIssue(\BugBundle\Entity\Issue $childrenIssues)
    {
        $this->childrenIssues->removeElement($childrenIssues);
    }

    /**
     * Get childrenIssues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildrenIssues()
    {
        return $this->childrenIssues;
    }

    /**
     * Set project
     *
     * @param \BugBundle\Entity\Project $project
     * @return Issue
     */
    public function setProject(\BugBundle\Entity\Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \BugBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add comments
     *
     * @param \BugBundle\Entity\IssueComment $comments
     * @return Issue
     */
    public function addComment(\BugBundle\Entity\IssueComment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \BugBundle\Entity\IssueComment $comments
     */
    public function removeComment(\BugBundle\Entity\IssueComment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
