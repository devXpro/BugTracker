<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 22.07.15
 * Time: 10:37
 */

namespace BugBundle\Tests\Unit\EventListener;


use BugBundle\Entity\Issue;
use BugBundle\Entity\IssueStatus;
use BugBundle\Entity\User;
use BugBundle\Event\BugEntityEvent;
use BugBundle\EventListener\IssueCommentListener;
use BugBundle\EventListener\IssueListener;
use BugBundle\Services\IssueActivityInterface;
use BugBundle\Tests\BugTypeTestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class IssueListenerTest extends BugTypeTestCase
{
    /** @var  IssueCommentListener */
    private $issueListener;
    /** @var  BugEntityEvent| \PHPUnit_Framework_MockObject_MockObject */
    private $event;

    private $user;
    /** @var  IssueActivityInterface| \PHPUnit_Framework_MockObject_MockObject */
    private $issueActivity;
    /** @var TokenStorage | \PHPUnit_Framework_MockObject_MockObject $tokenStorage */
    private $tokenStorage;

    private $token;

    public function setUp()
    {
        parent::setUp();
        /** @var TokenStorage $token */
        $this->user = new User();
        $this->token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');

        /** @var TokenStorage | \PHPUnit_Framework_MockObject_MockObject $tokenStorage */
        $this->tokenStorage = $this->getMock(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage'
        );


        /** @var IssueActivityInterface $issueCommentActivity */
        $this->issueActivity = $this->getMock('BugBundle\Services\IssueActivityInterface');
        $this->issueListener = new IssueListener($this->tokenStorage, $this->issueActivity);
        $this->event = $this->getMockBuilder('BugBundle\Event\BugEntityEvent')->disableOriginalConstructor()->getMock();


    }


    public function testOnAfterCreate()
    {
        $this->token->expects($this->any())->method('getUser')->will($this->returnValue($this->user));
        $this->tokenStorage->expects($this->any())->method('getToken')->will($this->returnValue($this->token));
        /** @var Issue |\PHPUnit_Framework_MockObject_MockObject $issueMock $issueMock */
        $issueMock = $this->getMock('BugBundle\Entity\Issue');
        $this->event->expects($this->any())->method('getEntity')->willReturn($issueMock);
        $issueMock->expects($this->once())->method('addCollaborator')->with($this->user);
        $this->issueActivity->expects($this->once())->method('markCreateIssue')->with($issueMock);
        $this->issueListener->onAfterCreate($this->event);

    }

    public function testOnAfterCreateWithoutToken()
    {
        $this->tokenStorage->expects($this->any())->method('getToken')->will($this->returnValue(null));
        /** @var Issue |\PHPUnit_Framework_MockObject_MockObject $issueMock $issueMock */
        $issueMock = $this->getMock('BugBundle\Entity\Issue');
        $this->event->expects($this->any())->method('getEntity')->willReturn($issueMock);
        $issueMock->expects($this->never())->method('addCollaborator');
        $this->issueActivity->expects($this->never())->method('markCreateIssue');
        $this->issueListener->onAfterCreate($this->event);

    }

    public function testOnUpdate()
    {
        $issueStatus1 = new IssueStatus();
        $issueStatus2 = clone $issueStatus1;
        $changes['status'][0] = $issueStatus1;
        $changes['status'][1] = $issueStatus2;

        $issueMock = $this->getMock('BugBundle\Entity\Issue');
        $this->event->expects($this->any())->method('getEntity')->willReturn($issueMock);
        /** @var UnitOfWork|\PHPUnit_Framework_MockObject_MockObject $uowMock */
        $uowMock = $this->getMockBuilder('Doctrine\ORM\UnitOfWork')->disableOriginalConstructor()->getMock();
        $uowMock->expects($this->once())->method('getEntityChangeSet')->with($issueMock)->will(
            $this->returnValue($changes)
        );
        /** @var EntityManager | \PHPUnit_Framework_MockObject_MockObject $emMock */
        $emMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $emMock->expects($this->once())->method('getUnitOfWork')->willReturn($uowMock);
        $this->event->expects($this->once())->method('getEm')->willReturn($emMock);
        $this->issueActivity->expects($this->once())->method('markChangeStatusIssue')->with(
            $issueMock,
            $issueStatus1,
            $issueStatus2
        );
        $this->issueListener->onUpdate($this->event);

    }

    public function testOnPreCreate()
    {
        $this->issueListener->onPreCreate($this->event);
    }

}
