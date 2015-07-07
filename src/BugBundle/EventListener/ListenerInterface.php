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
    public function onUpdate(BugEntityEvent $event);

    public function onCreate(BugEntityEvent $event);
}