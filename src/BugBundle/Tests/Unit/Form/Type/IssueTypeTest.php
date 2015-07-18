<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 16.07.15
 * Time: 18:06
 */

namespace BugBundle\Tests\Unit\Form\Type;


use BugBundle\Entity\Issue;

use BugBundle\Entity\IssuePriority;
use BugBundle\Entity\IssueResolution;
use BugBundle\Entity\IssueStatus;
use BugBundle\Entity\Project;
use BugBundle\Entity\User;

use BugBundle\Form\Type\IssueType;
use BugBundle\Tests\BugTypeTestCase;
use Symfony\Component\Form\PreloadedExtension;

class IssueTypeTest extends BugTypeTestCase
{
    /** @var  Project[] */
    private $projects;

    /** @var  IssuePriority[] */
    private $priorities;

    /** @var  IssueStatus[] */
    private $statuses;

    /** @var  IssueResolution[] */
    private $resolutions;

    /** @var  Issue[] */
    private $issues;


    /** @var  User[] */
    private $users;

    private $types = array(1 => 1, 2 => 2, 3 => 3);

    /**
     * @dataProvider formDataProvider
     * @param Issue $issue
     * @param $formData
     * @param User $user
     */
    public function testSubmitValidData(Issue $issue, $formData, User $user, $parentIssue)
    {

        $issueType = new IssueType($this->getTokenStorageWithUserMock($user));
        $form = $this->factory->create($issueType, null, array('parentIssue' => $parentIssue));
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $data = $form->getData();
        $this->assertEquals($issue, $form->getData());

    }

    /**
     * Wrong Argument check exception
     * @expectedException Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function testFormCreateException()
    {
        $user = $this->getEntity('BugBundle\Entity\User', array('username'));
        $IssueType = new IssueType($this->getTokenStorageWithUserMock($user));
        $this->factory->create($IssueType, null, array('parentIssue' => new \StdClass));

    }

    public function formDataProvider()
    {
        /** @var Issue $issue */
        $issue = $this->getEntity('BugBundle\Entity\Issue', array('code', 'summary', 'description'), true);
        /** @var User $user */
        //case 1
        $this->projects = $this->getEntitySet('BugBundle\Entity\Project', array('code'));
        $issue->setProject($this->projects[1]);
        $this->priorities = $this->getEntitySet('BugBundle\Entity\IssuePriority', array('label'));
        $issue->setPriority($this->priorities[1]);
        $this->statuses = $this->getEntitySet('BugBundle\Entity\IssueStatus', array('label'));
        $issue->setStatus($this->statuses[1]);
        $this->resolutions = $this->getEntitySet('BugBundle\Entity\IssueResolution', array('label'));
        $issue->setResolution($this->resolutions[1]);
        $issue->setType($this->types[1]);
        $this->users = $this->getEntitySet('BugBundle\Entity\User', array('username'), 10);
        $user = $this->users[1];
        $issue->setAssignee($this->users[2]);
        $issue->setReporter($user);

        //case 2
        $issue2 = clone $issue;
        $this->issues = $this->getEntitySet('BugBundle\Entity\Issue', array('code'));
        $parentIssue = $this->issues[1];
        $issue2->setParentIssue($parentIssue);

        $formData1 = $this->entityToFormData($issue, array('collaborators', 'comments'));
        $formData2 = $this->entityToFormData($issue2, array('collaborators', 'comments'));

        $dataProvider = [
            [$issue, $formData1, $user, null],
            [$issue2, $formData2, $user, $parentIssue],
        ];

        return $dataProvider;
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {


        $this->formDataProvider();
        $paramsSet = array(
            [$this->projects, 'bug_select_project'],
            [$this->types, 'bug_select_issue_type'],
            [$this->priorities, 'bug_select_issue_priority'],
            [$this->statuses, 'bug_select_issue_status'],
            [$this->resolutions, 'bug_select_issue_resolution'],
            [$this->users, 'bug_select_user'],
            [$this->issues, 'bug_set_parent_issue'],
        );
        $stubs = $this->getEntityStubs($paramsSet);

        return array(
            new PreloadedExtension($stubs, array()),
        );
    }

}
