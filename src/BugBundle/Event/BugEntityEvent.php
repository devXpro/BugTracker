<?php

namespace BugBundle\Event;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\Event;

class BugEntityEvent extends Event
{
    /** @var EntityManager */
    private $em;
    /** @var object */
    private $entity;

    /**
     * @param LifecycleEventArgs $args
     */
    public function __construct(LifecycleEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        $this->entity = $args->getEntity();
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
