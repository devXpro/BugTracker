<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 21.07.15
 * Time: 18:42
 */

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
