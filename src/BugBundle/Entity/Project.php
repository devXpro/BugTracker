<?php

namespace BugBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Project
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BugBundle\Entity\ProjectRepository")
 */
class Project
{
    public function __construct()
    {
        $this->issues = new ArrayCollection();
        $this->members = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLabel();
    }

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
     * @Assert\Length(min=5)
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     * @ORM\Column(name="summary", type="string", length=10000)
     */
    private $summary;

    /**
     * @var string
     * @Assert\Length(min=3)
     * @Assert\Length(max=3)
     * @ORM\Column(name="code", type="string", length=10)
     */
    private $code;

    /**
     *
     * @var Collection | Issue[]
     * @ORM\OneToMany(targetEntity="BugBundle\Entity\Issue", mappedBy="project", cascade={"remove"})
     */
    private $issues;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="BugBundle\Entity\User")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id", nullable=false)
     **/
    private $creator;

    /**
     * @var Collection | User[]
     * @ORM\ManyToMany(targetEntity="BugBundle\Entity\User", inversedBy="projects")
     * @ORM\JoinTable(name="projects_members")
     **/
    private $members;

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
     * Set label
     *
     * @param string $label
     * @return Project
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Project
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
     * @return Project
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
     * Add issues
     *
     * @param Issue $issues
     * @return Project
     */
    public function addIssue(Issue $issues)
    {
        $this->issues[] = $issues;

        return $this;
    }

    /**
     * Remove issues
     *
     * @param Issue $issues
     */
    public function removeIssue(Issue $issues)
    {
        $this->issues->removeElement($issues);
    }

    /**
     * Get issues
     *
     * @return Collection
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * Add members
     *
     * @param User $members
     * @return Project
     */
    public function addMember(User $members)
    {
        $this->members[] = $members;

        return $this;
    }

    /**
     * Remove members
     *
     * @param User $members
     */
    public function removeMember(User $members)
    {
        $this->members->removeElement($members);
    }

    /**
     * Get members
     *
     * @return Collection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Set creator
     *
     * @param User $creator
     * @return Project
     */
    public function setCreator(User $creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }
}
