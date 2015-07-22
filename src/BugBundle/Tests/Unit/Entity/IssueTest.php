<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 21.07.15
 * Time: 17:19
 */

namespace BugBundle\Tests\Unit\Entity;


use BugBundle\Entity\Issue;
use BugBundle\Entity\IssueComment;
use BugBundle\Entity\IssuePriority;
use BugBundle\Entity\IssueResolution;
use BugBundle\Entity\IssueStatus;
use BugBundle\Entity\Project;
use BugBundle\Entity\User;
use BugBundle\Tests\BugTestCase;
use Doctrine\Common\Collections\ArrayCollection;

class IssueTest extends BugTestCase
{

    /**
     *
     */
    public function testEntity()
    {
        $issue = new Issue();
        $issueStatus = new IssueStatus();
        $issueResolution = new IssueResolution();
        $issuePriority = new IssuePriority();
        $reporter = new User();
        $assignee = new User();
        $code = '123';
        $now = new \DateTime('now');
        $project = new Project();
        $summary = 'summary';
        $desc = 'desc';
        $collaborator = new User();
        $collaborators = new ArrayCollection();
        $collaborators->add($collaborator);
        $childrenIssue = new Issue();
        $comment = new IssueComment();
        $comments = new ArrayCollection();
        $comments->add($comment);
        $parentIssue = new Issue();

        $issue->setUpdatedNow();
        $issue->setCreatedNow();
        $issue->setPriority($issuePriority);
        $issue->setResolution($issueResolution);
        $issue->setType(Issue::TYPE_BUG);
        $issue->setStatus($issueStatus);
        $issue->setReporter($reporter);
        $issue->setCode($code);
        $issue->setDescription($desc);
        $issue->setAssignee($assignee);
        $issue->setProject($project);
        $issue->setSummary($summary);
        $issue->setCreated($now);
        $issue->addCollaborator($collaborator);
        $issue->addCollaborator($collaborator);
        $issue->addChildrenIssue($childrenIssue);
        $issue->addComment($comment);
        $issue->setParentIssue($parentIssue);
        $issue->setUpdated($now);


        $this->assertEquals($issuePriority, $issue->getPriority());
        $this->assertEquals($issueResolution, $issue->getResolution());
        $this->assertEquals(Issue::TYPE_BUG, $issue->getType());
        $this->assertEquals($issueStatus, $issue->getStatus());
        $this->assertEquals($reporter, $issue->getReporter());
        $this->assertEquals($code, $issue->getCode());
        $this->assertEquals($desc, $issue->getDescription());
        $this->assertEquals($assignee, $issue->getAssignee());
        $this->assertEquals($project, $issue->getProject());
        $this->assertEquals($summary, $issue->getSummary());
        $this->assertEquals($now, $issue->getCreated());
        $this->assertEquals($now, $issue->getUpdated());
        $this->assertEquals($collaborators, $issue->getCollaborators());
        $this->assertEquals($comments, $issue->getComments());
        $this->assertEquals($parentIssue, $issue->getParentIssue());
        $this->assertEquals('', $issue->__toString());
        $this->assertEquals(null, $issue->getId());


        $this->assertEquals(array($childrenIssue), $issue->getChildrenIssues()->toArray());
        $issue->removeCollaborator($collaborator);
        $issue->removeChildrenIssue($childrenIssue);
        $issue->removeComment($comment);
        $this->assertNotEquals($collaborators, $issue->getCollaborators());
        $this->assertNotEquals($comments, $issue->getComments());
        $this->assertNotEquals(array($childrenIssue), $issue->getChildrenIssues()->toArray());

        $issue->setType(Issue::TYPE_BUG);
        $this->assertEquals('bug', $issue->getTypeName());
        $issue->setType(Issue::TYPE_SUBTASK);
        $this->assertEquals('subtask', $issue->getTypeName());
        $issue->setType(Issue::TYPE_STORY);
        $this->assertEquals('story', $issue->getTypeName());
        $issue->setType(Issue::TYPE_TASK);
        $this->assertEquals('task', $issue->getTypeName());
        $issue->setType(0);
        $this->assertNull($issue->getTypeName());

    }

}
