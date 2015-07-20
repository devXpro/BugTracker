<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 20.07.15
 * Time: 13:34
 */

namespace BugBundle\Tests\Unit\Form\Type;


use BugBundle\Entity\User;
use BugBundle\Form\Type\ProjectSelectType;
use BugBundle\Tests\BugTypeTestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ProjectSelectTypeTest extends BugTypeTestCase
{
    public function testSelector()
    {
        /** @var AuthorizationCheckerInterface| \PhpUnit_Framework_MockObject_MockObject $authMock */
        $authMock = $this->getMock('Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface');
        $authMock->expects($this->once())->method('isGranted')->will($this->returnValue(true));
        $selectType = new ProjectSelectType($this->getTokenStorageWithUserMock(new User()), $authMock);
        $this->checkSelectors($selectType);
    }
}
