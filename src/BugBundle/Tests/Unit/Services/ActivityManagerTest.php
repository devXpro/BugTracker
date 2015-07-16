<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 14.07.15
 * Time: 18:23
 */

namespace BugBundle\Tests\Unit\Services;

use BugBundle\Entity\Activity;
use BugBundle\Entity\Issue;
use BugBundle\Entity\IssueComment;
use BugBundle\Entity\IssueStatus;
use BugBundle\Entity\User;
use BugBundle\Services\ActivityManager;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ActivityManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  TranslatorInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $translator;
    /** @var  ManagerRegistry|\PHPUnit_Framework_MockObject_MockObject */
    protected $doctrine;
    /** @var  TokenStorageInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $token;
    /** @var  RouterInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $router;
    /** @var  ActivityManager */
    protected $activityManager;

    /**
     *
     */
    protected function setUp()
    {
        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $this->doctrine = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $this->token = $this->getMock(
            'Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface'
        );
        $this->router = $this->getMock('Symfony\Component\Routing\RouterInterface');
        $this->activityManager = new ActivityManager($this->translator, $this->doctrine, $this->token, $this->router);

    }

    /**
     * @dataProvider markCreateIssueDataProvider
     * @param Issue $issue
     * @param Activity $activity
     * @param TokenInterface|\PHPUnit_Framework_MockObject_MockObject $token
     */
    public function testMarkCreateIssue(Issue $issue, Activity $activity, TokenInterface $token)
    {
        $this->token->expects($this->any())->method('getToken')->will($this->returnValue($token));
        $em = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->doctrine->expects($this->once())->method('getManagerForClass')->with($this->isType('string'))->will(
            $this->returnValue($em)
        );
        $em->expects($this->once())->method('persist')->with($activity);
        $em->expects($this->once())->method('flush');
        $this->activityManager->markCreateIssue($issue);

    }

    /**
     * markCreateIssueDataProvider
     * @return array
     */
    public function markCreateIssueDataProvider()
    {
        $issue = new Issue();
        $user = new User();

        return array(
            [
                $issue,
                (new Activity())->setType(Activity::TYPE_CREATE_ISSUE)->setIssue($issue)->setUser($user),
                $this->getToken($user),
            ],

        );
    }

    /**
     * @dataProvider markMarkCommentIssueDataProvider
     * @param IssueComment $issueComment
     * @param Activity $activity
     * @param TokenInterface $token
     */
    public function testMarkCommentIssue(IssueComment $issueComment, Activity $activity, TokenInterface $token)
    {
        $this->token->expects($this->any())->method('getToken')->will($this->returnValue($token));
        $em = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->doctrine->expects($this->once())->method('getManagerForClass')->with($this->isType('string'))->will(
            $this->returnValue($em)
        );
        $em->expects($this->once())->method('persist')->with($activity);
        $this->activityManager->markCommentIssue($issueComment);
    }


    /**
     * markCreateIssueDataProvider
     * @return array
     */
    public function markMarkCommentIssueDataProvider()
    {
        $issue = new Issue();
        $issueComment = new IssueComment();
        $issueComment->setIssue($issue);

        $user = new User();

        return array(
            [
                $issueComment,
                (new Activity())
                    ->setType(Activity::TYPE_COMMENT_ISSUE)
                    ->setComment($issueComment)
                    ->setIssue($issue)->setUser($user),
                $this->getToken($user),
            ],

        );
    }

    /**
     * @dataProvider changeStatusIssueDataProvider
     * @param Issue $issue
     * @param IssueStatus $oldStatus
     * @param IssueStatus $newStatus
     * @param Activity $activity
     * @param TokenInterface $token
     */
    public function testMarkChangeStatusIssue(
        Issue $issue,
        IssueStatus $oldStatus,
        IssueStatus $newStatus,
        Activity $activity,
        TokenInterface $token
    ) {
        $this->token->expects($this->any())->method('getToken')->will($this->returnValue($token));
        $em = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->doctrine->expects($this->once())->method('getManagerForClass')->with($this->isType('string'))->will(
            $this->returnValue($em)
        );
        $em->expects($this->once())->method('persist')->with($activity);
        $em->expects($this->once())->method('flush');
        $this->activityManager->markChangeStatusIssue($issue, $oldStatus, $newStatus);

    }


    /**
     * markCreateIssueDataProvider
     * @return array
     */
    public function changeStatusIssueDataProvider()
    {
        $issue = new Issue();
        $issueOldStatus = new IssueStatus();
        $issueNewStatus = new IssueStatus();

        $user = new User();

        return array(
            [
                $issue,
                $issueOldStatus,
                $issueNewStatus,
                (new Activity())
                    ->setType(Activity::TYPE_CHANGE_STATUS_ISSUE)
                    ->setOldStatus($issueOldStatus)
                    ->setNewStatus($issueNewStatus)
                    ->setIssue($issue)
                    ->setUser($user),
                $this->getToken($user),
            ],

        );
    }

    /**
     * @dataProvider testMarkCommentIssueExceptionNotFoundIssueDataProvider
     * @expectedException Doctrine\ORM\EntityNotFoundException
     * @param IssueComment $issueComment
     * @param TokenInterface $token
     */
    public function testMarkCommentIssueExceptionNotFoundIssue(IssueComment $issueComment, TokenInterface $token)
    {
        $this->token->expects($this->any())->method('getToken')->will($this->returnValue($token));
        $this->activityManager->markCommentIssue($issueComment);

    }

    public function testMarkCommentIssueExceptionNotFoundIssueDataProvider()
    {
        return array(
            [
                new IssueComment(),
                $this->getToken(new User()),
            ],

        );
    }


    /**
     * @dataProvider exceptionsProvider
     * @expectedException \Exception
     * @param TokenInterface |\PHPUnit_Framework_MockObject_MockObject $token
     * @param $expectedException string
     */
    public function testMarkCreateIssueExceptions(TokenInterface $token, $expectedException)
    {
        $this->token->expects($this->any())->method('getToken')->will($this->returnValue($token));
        $this->setExpectedException($expectedException);
        $this->activityManager->markCreateIssue(new Issue());
    }

    /**
     * @dataProvider exceptionsProvider
     * @expectedException \Exception
     * @param TokenInterface |\PHPUnit_Framework_MockObject_MockObject $token
     * @param $expectedException string
     */
    public function testMarkCommentIssueExceptions(TokenInterface $token, $expectedException)
    {
        $this->token->expects($this->any())->method('getToken')->will($this->returnValue($token));
        $this->setExpectedException($expectedException);
        $this->activityManager->markCommentIssue(new IssueComment());
    }

    /**
     * @dataProvider exceptionsProvider
     * @expectedException \Exception
     * @param TokenInterface |\PHPUnit_Framework_MockObject_MockObject $token
     * @param $expectedException string
     */
    public function testMarkChangeStatusIssueExceptions(TokenInterface $token, $expectedException)
    {
        $this->token->expects($this->any())->method('getToken')->will($this->returnValue($token));
        $this->setExpectedException($expectedException);
        $this->activityManager->markChangeStatusIssue(new Issue(), new IssueStatus(), new IssueStatus());

    }


    /**
     *
     */
    public function exceptionsProvider()
    {

        return array(
            [
                $this->getToken(null),
                'Symfony\Component\Security\Core\Exception\AuthenticationException',

            ],
            [
                $this->getToken('string'),
                'Doctrine\ORM\EntityNotFoundException',
            ],
            [
                null,
                'Symfony\Component\Security\Core\Exception\TokenNotFoundException',
            ],
            [
                $this->getToken(new \StdClass()),
                'Doctrine\ORM\EntityNotFoundException',
            ],
        );
    }

    /**
     * @param $user
     * @return TokenInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getToken($user)
    {

        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token->expects($this->any())->method('getUser')->will($this->returnValue($user));

        return $token;
    }
}

//$token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
//$this->token->expects($this->once())->method('getToken')->will($this->returnValue($token));
//
//$this->router->expects($this->once())->method('generate')->with(
//    $this->isType('string'),
//    $this->logicalAnd($this->isType('array'), $this->arrayHasKey('issue'), $this->contains($issue->getId()))
//)->will($this->returnArgument(0));
//$em = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
//$this->doctrine->expects($this->once())->method('getManagerForClass')->with($this->isType('string'))->will(
//    $this->returnValue($em)
//);
//$em->expects($this->once())->method('persist')->with($activity);
//$em->expects($this->once())->method('flush');
//$this->activityManager->markCreateIssue($issue);