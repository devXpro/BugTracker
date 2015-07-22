<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 07.07.15
 * Time: 15:20
 */

namespace BugBundle\EventListener;


use BugBundle\Event\BugEntityEvent;

interface ListenerInterface
{
    /**
     * handler update event
     * @param BugEntityEvent $event
     * @return void
     */
    public function onUpdate(BugEntityEvent $event);

    /**
     * handler pre create event
     * @param BugEntityEvent $event
     * @return void
     */
    public function onPreCreate(BugEntityEvent $event);

    /**
     * handler after create event
     * @param BugEntityEvent $event
     * @return void
     */
    public function onAfterCreate(BugEntityEvent $event);
}