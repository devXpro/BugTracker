<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 07.07.15
 * Time: 14:14
 */

namespace BugBundle\Event;


use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\Event;

class BugEntityEvent extends Event
{
    private $em;
    private $entity;

    public function __construct(LifecycleEventArgs $args)
    {
        $this->em = $args->getEntityManager();
        $this->entity = $args->getEntity();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
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