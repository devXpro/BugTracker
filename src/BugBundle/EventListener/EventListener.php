<?php

namespace BugBundle\EventListener;

use BugBundle\Event\BugEntityEvent;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventListener
{
    /** @var  LifecycleEventArgs */
    private $args;
    /** @var EventDispatcherInterface */
    private $dispacher;

    public function __construct(EventDispatcherInterface $dispatcherInterface)
    {
        $this->dispacher = $dispatcherInterface;
    }

    /**
     * @param LifecycleEventArgs $args
     * @return void
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->args = $args;
        $this->handleEvent('onAfterCreate');
    }

    /**
     * @param LifecycleEventArgs $args
     * @return void
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->args = $args;
        $this->handleEvent('onPreCreate');

    }

    /**
     * @param LifecycleEventArgs $args
     * @return void
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->args = $args;
        $this->handleEvent('onUpdate');
    }

    /**
     * @param $eventName
     * @return void
     */
    private function handleEvent($eventName)
    {
        $eventName = 'bug.entity.'.strtolower($this->getClassName($this->args->getEntity())).'.'.$eventName;
        $event = new BugEntityEvent($this->args);
        $this->dispacher->dispatch($eventName, $event);

    }

    /**
     * @param $class
     * @return string
     */
    private function getClassName($class)
    {
        $classname = get_class($class);
        $pos = strrpos($classname, '\\');

        return substr($classname, $pos + 1);
    }
}
