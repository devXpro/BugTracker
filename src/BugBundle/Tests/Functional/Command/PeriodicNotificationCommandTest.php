<?php

namespace BugBundle\Tests\Functional\Command;

use BugBundle\Command\PeriodicNotificationCommand;
use BugBundle\Entity\Activity;
use BugBundle\Entity\Issue;
use BugBundle\Entity\IssuePriority;
use BugBundle\Entity\Project;
use BugBundle\Tests\BugTestCase;
use Doctrine\Bundle\DoctrineBundle\Registry;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class PeriodicNotificationCommandTest extends BugTestCase
{
    private static $project;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {

        $client = static::createClient();
        /** @var Registry em */
        $em = $client->getContainer()->get('doctrine')->getManager();
        $user = $user = $em->getRepository('BugBundle:User')->findOneBy(array('username' => 'user'));
        $activity = new Activity();
        self::$project = $project = new Project();
        $project->setSummary('asdfasdf')->setLabel('asdfasdf')->setCreator($user)->setCode('1WE');
        $issuePriority = $em->getRepository('BugBundle:IssuePriority')->findOneBy(array('label' => 'High'));
        $issueResolution = $em->getRepository('BugBundle:IssueResolution')->findOneBy(array('label' => 'Invalid'));
        $issueStatus = $em->getRepository('BugBundle:IssueStatus')->findOneBy(array('label' => 'Closed'));
        $issue = new Issue();
        $issue->addCollaborator($user);
        $issue->setSummary('asdf')->setCode('123')->setAssignee($user)->setDescription('sdf')
            ->setProject($project)->setType(Issue::TYPE_BUG)->setPriority($issuePriority)
            ->setResolution($issueResolution)->setStatus($issueStatus)->setReporter($user);
        $activity->setUser($user)->setIssue($issue)->setType(Activity::TYPE_CREATE_ISSUE);
        $em->persist($issue);
        $em->persist($project);
        $em->persist($activity);
        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getManager();
        $em->remove($em->find('BugBundle:Project', self::$project->getId()));
        $em->flush();
    }


    public function testExecute()
    {
        $client = static::createClient();
        $kernel = $client->getKernel();
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $application->add(new PeriodicNotificationCommand());
        $command = $application->find('bug:collaborators:notify');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));
        $this->assertRegExp('/Start/', $commandTester->getDisplay());
        $this->assertNotRegExp('/Nothing to send/', $commandTester->getDisplay());

        $commandTester->execute(array('command' => $command->getName()));
        $this->assertRegExp('/Nothing to send/', $commandTester->getDisplay());
    }
}
