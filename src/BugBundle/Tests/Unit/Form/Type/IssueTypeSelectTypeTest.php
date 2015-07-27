<?php

namespace BugBundle\Tests\Unit\Form\Type;

use BugBundle\Form\Type\IssueTypeSelectType;
use BugBundle\Services\TransHelper;
use BugBundle\Tests\BugTypeTestCase;

class IssueTypeSelectTypeTest extends BugTypeTestCase
{
    public function testSelector()
    {
        /** @var TransHelper |\PHPUnit_Framework_MockObject_MockObject $transMock */
        $transMock = $this->getMockBuilder('BugBundle\Services\TransHelper')->disableOriginalConstructor()->getMock();
        $transMock->expects($this->any())->method('transUp')->with($this->isType('string'))->will(
            $this->returnArgument(0)
        );
        $selectType = new IssueTypeSelectType($transMock);
        $this->checkSelectors($selectType);
    }
}
