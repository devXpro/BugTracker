<?php

namespace BugBundle\Tests\Unit\Security;

use BugBundle\Controller\IssueController;
use BugBundle\Entity\Issue;
use BugBundle\Entity\ProjectRepository;
use BugBundle\Entity\User;
use BugBundle\Security\IssueCanCreateAnyVoter;
use BugBundle\Security\IssueCanCreateChildrenVoter;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class IssueVoterTest extends \PHPUnit_Framework_TestCase
{
    /** @var EntityManager | \PHPUnit_Framework_MockObject_MockObject $emMock */
    private $emMock;
    /** @var  IssueCanCreateAnyVoter */
    private $issueVoter;
    /** @var TokenInterface|\PHPUnit_Framework_MockObject_MockObject $token */
    private $token;

    public function setUp()
    {
        parent::setUp();


        $this->emMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        /** @var Registry  | \PHPUnit_Framework_MockObject_MockObject $registryMock */
        $registryMock = $this->getMockBuilder('Doctrine\Bundle\DoctrineBundle\Registry')->disableOriginalConstructor(
        )->getMock();
        $registryMock->expects($this->any())->method('getManager')->will($this->returnValue($this->emMock));
        $this->token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->issueVoter = new IssueVoter($registryMock);


    }

    /**
     * @dataProvider dataProvider
     * @param $token
     * @param $obj
     * @param $attr
     */
    public function testVote($token, $obj, $attr, $expectedResult)
    {
        $result = $this->issueVoter->vote($token, $obj, $attr);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        /** @var Registry  | \PHPUnit_Framework_MockObject_MockObject $registryMock */
        $tokenTrue = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $tokenFalse = clone $tokenTrue;
        $tokenTrue->expects($this->any())->method('getUser')->willReturn(new User());

        $objTrue = new Issue();
        $objFalse = new  \StdClass();

        $attrTrue = array(IssueCanCreateChildrenVoter::CAN_CREATE_CHILDREN_ISSUE);
        $attrFalse = array('Some Shit');

        return [

            [
                $tokenTrue,
                $objTrue,
                $attrFalse,
                false,
            ],
            [
                $tokenFalse,
                $objTrue,
                $attrTrue,
                false,
            ],
        ];
    }

    public function testCaseCreateIssue()
    {

        /** @var User |\PHPUnit_Framework_MockObject_MockObject $user */
        $user = $this->getMock('BugBundle\Entity\User');
        $user->expects($this->once())->method('getRoles')->willReturn(array('Another Role'));
        $this->token->expects($this->any())->method('getUser')->willReturn($user);

        /** @var ProjectRepository | \PHPUnit_Framework_MockObject_MockObject $projectRepo */
        $projectRepo = $this->getMockBuilder('BugBundle\Entity\ProjectRepository')->disableOriginalConstructor(
        )->getMock();
        $projectRepo->expects($this->once())->method('getProjectsByUser')->with($user)->will(
            $this->returnValue(array())
        );
        $this->emMock->expects($this->once())->method('getRepository')->with('BugBundle:Project')->will(
            $this->returnValue($projectRepo)
        );
        $this->assertFalse($this->issueVoter->vote($this->token, null, array(IssueCanCreateAnyVoter::CREATE_ISSUE)));
    }


    public function testCaseCreateChildrenIssue()
    {
        /** @var User |\PHPUnit_Framework_MockObject_MockObject $user */
        $user = $this->getMock('BugBundle\Entity\User');
        $user->expects($this->any())->method('getRoles')->willReturn(array('Another Role'));
        $this->token->expects($this->any())->method('getUser')->willReturn($user);
        /** @var Issue |\PHPUnit_Framework_MockObject_MockObject $user */
        $obj = $this->getMock('BugBundle\Entity\Issue');
        $obj->expects($this->any())->method('getType')->willReturn(Issue::TYPE_BUG);
        $wrongObj = new \StdClass();
        /** @var ProjectRepository |  \PHPUnit_Framework_MockObject_MockObject $projectRepo */
        $IssueRepo = $this->getMockBuilder('BugBundle\Entity\IssueRepository')->disableOriginalConstructor()->getMock();
        $IssueRepo->expects($this->any())->method('find')->with($wrongObj)->will(
            $this->returnValue($obj)
        );
        $this->emMock->expects($this->any())->method('getRepository')->with('BugBundle:Issue')->will(
            $this->returnValue($IssueRepo)
        );
        $this->assertFalse(
            $this->issueVoter->vote(
                $this->token,
                $wrongObj,
                array(IssueCanCreateChildrenVoter::CAN_CREATE_CHILDREN_ISSUE)
            )
        );
    }

    public function testCaseCreateChildrenIssueTrue()
    {
        /** @var User |\PHPUnit_Framework_MockObject_MockObject $user */
        $user = $this->getMock('BugBundle\Entity\User');
        $user->expects($this->any())->method('getRoles')->willReturn(array('Another Role'));
        $this->token->expects($this->any())->method('getUser')->willReturn($user);
        /** @var Issue |\PHPUnit_Framework_MockObject_MockObject $user */
        $obj = $this->getMock('BugBundle\Entity\Issue');
        $obj->expects($this->any())->method('getType')->willReturn(Issue::TYPE_STORY);
        $wrongObj = new \StdClass();
        /** @var ProjectRepository |  \PHPUnit_Framework_MockObject_MockObject $projectRepo */
        $IssueRepo = $this->getMockBuilder('BugBundle\Entity\IssueRepository')->disableOriginalConstructor()->getMock();
        $IssueRepo->expects($this->any())->method('find')->with($wrongObj)->will(
            $this->returnValue($obj)
        );
        $this->emMock->expects($this->any())->method('getRepository')->with('BugBundle:Issue')->will(
            $this->returnValue($IssueRepo)
        );
        $this->assertTrue(
            $this->issueVoter->vote(
                $this->token,
                $wrongObj,
                array(IssueCanCreateChildrenVoter::CAN_CREATE_CHILDREN_ISSUE)
            )
        );
    }

    public function testSupportAttr()
    {
        $this->assertTrue($this->issueVoter->supportsAttribute(IssueCanCreateChildrenVoter::CAN_CREATE_CHILDREN_ISSUE));
    }

    public function testSupportsClass()
    {
        $this->assertFalse($this->issueVoter->supportsClass(new \StdClass()));
        /** @var IssueController $controller */
        $controller = $this->getMock('BugBundle\Controller\IssueController');
        $this->assertTrue($this->issueVoter->supportsClass($controller));
    }
}
