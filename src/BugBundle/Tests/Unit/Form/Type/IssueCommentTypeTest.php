<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 16.07.15
 * Time: 18:06
 */

namespace BugBundle\Tests\Unit\Form\Type;


use BugBundle\Entity\Issue;
use BugBundle\Entity\IssueComment;
use BugBundle\Entity\User;

use BugBundle\Form\Type\IssueCommentType;
use BugBundle\Tests\BugTypeTestCase;
use BugBundle\Tests\EntityTypeStub;
use Symfony\Component\Form\PreloadedExtension;

class IssueCommentTypeTest extends BugTypeTestCase
{
    /** @var  []Issue */
    private $issues;

    /** @var  Issue */
    private $selectedIssue;

    /** @var  []User */
    private $users;

    /**
     * @dataProvider formDataProvider
     * @param IssueComment $issueComment
     * @param $formData
     * @param User $user
     */
    public function testSubmitValidData(IssueComment $issueComment, $formData, User $user)
    {
        $issueCommentType = new IssueCommentType($this->getTokenStorageWithUserMock($user));
        $form = $this->factory->create($issueCommentType, null, array('issue' => $this->selectedIssue));
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($issueComment, $form->getData());

    }

    /**
     * Wrong Argument check exception
     * @expectedException Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function testFormCreateException()
    {
        $user = $this->getEntity('BugBundle\Entity\User', array('username'));
        $issueCommentType = new IssueCommentType($this->getTokenStorageWithUserMock($user));
        $this->factory->create($issueCommentType, null, array('issue' => new \StdClass));

    }

    public function formDataProvider()
    {
        /** @var User $user */
        $this->users = $this->getEntitySet('BugBundle\Entity\User', array('username'));
        $user = $this->users[1];
        $issueComment = new IssueComment();
        $this->issues = $this->getEntitySet('BugBundle\Entity\Issue', array('summary', 'code'));
        $this->selectedIssue = $this->issues[1];
        $issueComment->setIssue($this->selectedIssue);
        $issueComment->setAuthor($user);
        $issueComment->setBody('My comment');

        return [
            [
                $issueComment,
                array(
                    'issue' => $issueComment->getIssue()->getId(),
                    'body' => $issueComment->getBody(),
                    'author' => $user->getId(),
                ),
                $user,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getExtensions()
    {
        $this->formDataProvider();
        $issuesStub = new EntityTypeStub($this->issues, 'bug_select_issue');
        $usersStub = new EntityTypeStub($this->users, 'bug_select_user');

        return array(
            new PreloadedExtension(
                array(
                    $issuesStub->getName() => $issuesStub,
                    $usersStub->getName() => $usersStub,
                ), array()
            ),
        );
    }

}
