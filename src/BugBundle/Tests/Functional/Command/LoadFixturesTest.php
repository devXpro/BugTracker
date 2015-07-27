<?php

namespace BugBundle\Tests\Functional\Command;

use BugBundle\DataFixtures\ORM\LoadRolesAndUsers;
use BugBundle\Tests\BugTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class LoadFixturesTest extends BugTestCase
{
    public function testLoadFixtures()
    {
        $client = static::createClient();
        $fix = new LoadRolesAndUsers;

        $fix->setContainer($client->getContainer());
        $em = $client->getContainer()->get('doctrine')->getManager();
        $purger = new ORMPurger($em);
        $purger->setPurgeMode($purger::PURGE_MODE_TRUNCATE);
        $purger->purge();
        $this->assertTrue($fix->load($em));
    }
}
