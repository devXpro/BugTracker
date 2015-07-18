<?php
///**
// * Created by PhpStorm.
// * User: roma
// * Date: 16.07.15
// * Time: 18:06
// */
//
//namespace BugBundle\Tests\Unit\Form\Type;
//
//
//use BugBundle\Entity\Issue;
//
//use BugBundle\Entity\IssuePriority;
//use BugBundle\Entity\IssueResolution;
//use BugBundle\Entity\IssueStatus;
//use BugBundle\Entity\Project;
//use BugBundle\Entity\User;
//
//use BugBundle\Form\Type\IssueType;
//use BugBundle\Tests\BugTypeTestCase;
//use BugBundle\Tests\EntityTypeStub;
//use Symfony\Component\Form\PreloadedExtension;
//
//class IssueTypeTest extends BugTypeTestCase
//{
//    /** @var  Project[] */
//    private $projects;
//
//    /** @var  IssuePriority[] */
//    private $priorities;
//
//    /** @var  IssueStatus[] */
//    private $statuses;
//
//    /** @var  IssueResolution[] */
//    private $resolutions;
//
//    /** @var  Issue[] */
//    private $issues;
//
//    /** @var  User[] */
//    private $users;
//
//    /**
//     * @dataProvider formDataProvider
//     * @param Issue $issue
//     * @param $formData
//     * @param User $user
//     */
//    public function testSubmitValidData(Issue $issue, $formData, User $user)
//    {
//
//        $issueType = new IssueType($this->getTokenStorageWithUserMock($user));
//        $form = $this->factory->create($issueType, null, array('issue' => $this->selectedIssue));
//        $form->submit($formData);
//        $this->assertTrue($form->isSynchronized());
//        $this->assertEquals($issue, $form->getData());
//
//    }
//
//    /**
//     * Wrong Argument check exception
//     * @expectedException Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
//     */
//    public function testFormCreateException()
//    {
//        $user = $this->getEntity('BugBundle\Entity\User', array('username'));
//        $IssueType = new IssueType($this->getTokenStorageWithUserMock($user));
//        $this->factory->create($IssueType, null, array('issue' => new \StdClass));
//
//    }
//
//    public function formDataProvider()
//    {
//        /** @var Issue $issue */
//        $issue = $this->getEntity('BugBundle\Entity\Issue', array('code', 'summary', 'description'), true);
//        /** @var User $user */
//
//        $this->projects = $this->getEntitySet('BugBundle\Entity\Project', array('code'));
//        $issue->setProject($this->projects[1]);
//        $this->priorities = $this->getEntitySet('BugBundle\Entity\Project', array('label'));
//        $issue->setPriority($this->priorities[1]);
//        $this->statuses = $this->getEntitySet('BugBundle\Entity\IssueStatus', array('label'));
//        $issue->setStatus($this->statuses[1]);
//        $this->resolutions = $this->getEntitySet('BugBundle\Entity\IssueResolution', array('label'));
//
//        $this->users = $this->getEntitySet('BugBundle\Entity\User', array('username'), 10);
//        $user = $this->users[1];
//        $issue->setAssignee($this->users[2]);
//        $issue->setReporter($user);
//
//
//        $issue2 = clone $issue;
//        $this->issues = $this->getEntitySet('BugBundle\Entity\Issue', array('code'));
//        $issue2->setParentIssue($this->issues[1]);
//
//
//        return [
//            [
//                $issue,
//                array(
//                    'issue' => $issue->getIssue()->getId(),
//                    'body' => $issue->getBody(),
//                    'author' => $user->getId(),
//                ),
//                $user,
//            ],
//        ];
//    }
//
//    /**
//     * @return array
//     */
//    protected function getExtensions()
//    {
//        $this->formDataProvider();
//        $issuesStub = new EntityTypeStub($this->issues, 'bug_select_issue');
//        $usersStub = new EntityTypeStub($this->users, 'bug_select_user');
//
//        return array(
//            new PreloadedExtension(
//                array(
//                    $issuesStub->getName() => $issuesStub,
//                    $usersStub->getName() => $usersStub,
//                ), array()
//            ),
//        );
//    }
//
//}
