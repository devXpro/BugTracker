<?php

namespace BugBundle\Tests\Unit\Event;

use BugBundle\Event\BugEntityEvent;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\Entity;

/**
 * Created by PhpStorm.
 * User: roma
 * Date: 21.07.15
 * Time: 21:44
 */
class BugEntityEventTest extends \PHPUnit_Framework_TestCase
{

    /** @var  BugEntityEvent */
    private $event;
    private $em;
    private $entity;

    public function setUp()
    {
        parent::setUp();
        /** @var LifecycleEventArgs | \PHPUnit_Framework_MockObject_MockObject $argsMock */
        $argsMock = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')->disableOriginalConstructor(
        )->getMock();

        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->entity = 'entity';
        $argsMock->expects($this->once())->method('getEntityManager')->will($this->returnValue($this->em));
        $argsMock->expects($this->once())->method('getEntity')->will($this->returnValue($this->entity));
        $this->event = new BugEntityEvent($argsMock);
    }

    public function testEvent()
    {
        $this->assertEquals($this->em, $this->event->getEm());
        $this->assertEquals($this->entity, $this->event->getEntity());
    }


}
