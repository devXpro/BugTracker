<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 21.07.15
 * Time: 18:42
 */

namespace BugBundle\Tests\Unit\Entity;


use BugBundle\Entity\IssuePriority;

class IssuePriorityTest extends \PHPUnit_Framework_TestCase
{
    public function testEntity()
    {
        $label = 'label';
        $issuePriority = new IssuePriority();
        $issuePriority->setLabel($label);

        $this->assertEquals($label, $issuePriority->getLabel());
        $this->assertNull($issuePriority->getId());
        $this->assertEquals($label, $issuePriority->__toString());
    }
}
