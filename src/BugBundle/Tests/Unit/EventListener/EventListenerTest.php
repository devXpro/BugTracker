<?php

namespace BugBundle\Tests\Unit\EventListener;

use BugBundle\Entity\User;
use BugBundle\EventListener\EventListener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EventListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  EventDispatcher| \PHPUnit_Framework_MockObject_MockObject */
    private $ed;
    /** @var  EventListener */
    private $el;

    public function setUp()
    {
        parent::setUp();
        $this->ed = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->ed->expects($this->any())->method('dispatch')->with(
            $this->isType('string'),
            $this->isInstanceOf('BugBundle\Event\BugEntityEvent')
        );
        $this->el = new EventListener($this->ed);
    }

    public function testEvents()
    {
        /** @var  LifecycleEventArgs|\PHPUnit_Framework_MockObject_MockObject $args */
        $args = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')->disableOriginalConstructor()->getMock();
        $entity = new User();
        $args->expects($this->any())->method('getEntity')->will($this->returnValue($entity));
        $this->el->postPersist($args);
        $this->el->prePersist($args);
        $this->el->postUpdate($args);
    }
}
