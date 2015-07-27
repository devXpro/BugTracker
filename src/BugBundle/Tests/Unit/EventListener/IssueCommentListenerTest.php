<?php

namespace BugBundle\Tests\Unit\EventListener;

use BugBundle\Entity\Issue;
use BugBundle\Entity\IssueComment;
use BugBundle\Entity\User;
use BugBundle\Event\BugEntityEvent;
use BugBundle\EventListener\IssueCommentListener;
use BugBundle\Services\IssueCommentActivityInterface;
use BugBundle\Tests\BugTypeTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class IssueCommentListenerTest extends BugTypeTestCase
{
    /** @var  IssueCommentListener */
    private $issueCommentListener;
    /** @var  BugEntityEvent| \PHPUnit_Framework_MockObject_MockObject */
    private $event;
    /** @var  User */
    private $user;
    /** @var  IssueCommentActivityInterface| \PHPUnit_Framework_MockObject_MockObject */
    private $issueCommentActivity;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        /** @var TokenStorage $token */
        $this->user = new User();
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token->expects($this->any())->method('getUser')->will($this->returnValue($this->user));
        /** @var TokenStorage | \PHPUnit_Framework_MockObject_MockObject $tokenStorage */
        $tokenStorage = $this->getMock(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage'
        );
        $tokenStorage->expects($this->any())->method('getToken')->will($this->returnValue($token));

        /** @var IssueCommentActivityInterface $issueCommentActivity */
        $this->issueCommentActivity = $this->getMock('BugBundle\Services\IssueCommentActivityInterface');
        $this->issueCommentListener = new IssueCommentListener($tokenStorage, $this->issueCommentActivity);
        $this->event = $this->getMockBuilder('BugBundle\Event\BugEntityEvent')->disableOriginalConstructor()->getMock();
    }

    public function testOnPreCreate()
    {
        /** @var Issue |\PHPUnit_Framework_MockObject_MockObject $issueCommentMock $issueMock */
        $issueMock = $this->getMock('BugBundle\Entity\Issue');
        $issueMock->expects($this->once())->method('addCollaborator')->with($this->user);
        /** @var IssueComment|\PHPUnit_Framework_MockObject_MockObject $issueCommentMock */
        $issueCommentMock = $this->getMock('BugBundle\Entity\IssueComment');
        $issueCommentMock->expects($this->once())->method('getIssue')->will($this->returnValue($issueMock));
        $this->event->expects($this->any())->method('getEntity')->willReturn($issueCommentMock);
        $this->issueCommentActivity->expects($this->once())->method('markCommentIssue')->with($issueCommentMock);
        $this->issueCommentListener->onPreCreate($this->event);
    }

    public function testOnUpdate()
    {
        $this->issueCommentListener->onUpdate($this->event);
    }

    public function onAfterCreate()
    {
        $this->issueCommentListener->onAfterCreate($this->event);
    }
}
