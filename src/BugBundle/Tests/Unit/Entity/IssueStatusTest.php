<?php

namespace BugBundle\Tests\Unit\Entity;

use BugBundle\Entity\IssueStatus;

class IssueStatusTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $label = 'label';
        $issueStatus = new IssueStatus();
        $issueStatus->setLabel($label);

        $this->assertEquals($label, $issueStatus->getLabel());
        $this->assertNull($issueStatus->getId());
        $this->assertEquals($label, $issueStatus->__toString());
    }
}
