<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 06.07.15
 * Time: 19:56
 */

namespace BugBundle\EventListener;

use BugBundle\Event\BugEntityEvent;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class EventListener
{

    /** @var  LifecycleEventArgs */
    private $args;
    private $dispacher;

    public function __construct(EventDispatcherInterface $dispatcherInterface)
    {
        $this->dispacher =$dispatcherInterface;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->args = $args;
        $this->handleEvent('onCreate');

    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->args = $args;
        $this->handleEvent('onUpdate');
    }


    private function handleEvent($eventName)
    {
        $eventName = 'bug.entity.' . strtolower($this->get_class_name($this->args->getEntity())) .'.'. $eventName;
        $event=new BugEntityEvent($this->args);
        $this->dispacher->dispatch($eventName,$event);

    }

    private function get_class_name($class)
    {
        $classname = get_class($class);
        if ($pos = strrpos($classname, '\\')) return substr($classname, $pos + 1);
        return $pos;
    }

}