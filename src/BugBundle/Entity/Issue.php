<?php

namespace BugBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank()
     * @Assert\Length(max=1000)
     * @ORM\Column(name="summary", type="string", length=1000)
     */
    private $summary;

    /**
     * @var string
     * @Assert\Length(min=3)
     * @Assert\Length(max=3)
     * @ORM\Column(name="code", type="string", length=3)
     */
    private $code;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=7)
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
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
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
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
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

    public function __construct()
    {
        $this->childrenIssues = new ArrayCollection();
        $this->collaborators = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getIssueFullName() ? $this->getIssueFullName() : '';
    }

    private function getIssueFullName()
    {

        return ($this->code && $this->id && $this->summary) ? $this->code.'-'.$this->id.' '.$this->summary : '';
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
    public function setCreatedNow()
    {
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
    public function setUpdatedNow()
    {
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
     * @param IssueStatus $status
     * @return Issue
     */
    public function setStatus(IssueStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return IssueStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set resolution
     *
     * @param IssueResolution $resolution
     * @return Issue
     */
    public function setResolution(IssueResolution $resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Get resolution
     *
     * @return IssueResolution
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set reporter
     *
     * @param User $reporter
     * @return Issue
     */
    public function setReporter(User $reporter)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set assignee
     *
     * @param User $assignee
     * @return Issue
     */
    public function setAssignee(User $assignee)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * Add collaborators
     *
     * @param User $collaborators
     * @return Issue
     */
    public function addCollaborator(User $collaborators)
    {
        foreach ($this->collaborators as $col) {
            if ($col == $collaborators) {
                return $this;
            }
        }

        $this->collaborators[] = $collaborators;

        return $this;
    }

    /**
     * Remove collaborators
     *
     * @param User $collaborators
     */
    public function removeCollaborator(User $collaborators)
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
     * @param Issue $parentIssue
     * @return Issue
     */
    public function setParentIssue(Issue $parentIssue)
    {
        $this->parentIssue = $parentIssue;

        return $this;
    }

    /**
     * Get parentIssue
     *
     * @return Issue
     */
    public function getParentIssue()
    {
        return $this->parentIssue;
    }

    /**
     * Add childrenIssues
     *
     * @param Issue $childrenIssues
     * @return Issue
     */
    public function addChildrenIssue(Issue $childrenIssues)
    {
        $this->childrenIssues[] = $childrenIssues;

        return $this;
    }

    /**
     * Remove childrenIssues
     *
     * @param Issue $childrenIssues
     */
    public function removeChildrenIssue(Issue $childrenIssues)
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
     * @param Project $project
     * @return Issue
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add comments
     *
     * @param IssueComment $comments
     * @return Issue
     */
    public function addComment(IssueComment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param IssueComment $comments
     */
    public function removeComment(IssueComment $comments)
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

    public function getTypeName()
    {
        switch ($this->type) {
            case self::TYPE_TASK:
                return 'task';
            case self::TYPE_BUG:
                return 'bug';
            case self::TYPE_SUBTASK:
                return 'subtask';
            case self::TYPE_STORY:
                return 'story';
        }
    }
}
