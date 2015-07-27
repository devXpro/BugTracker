<?php

namespace BugBundle\Tests\Functional\Entity;

use BugBundle\Entity\Issue;
use BugBundle\Entity\IssueStatus;
use BugBundle\Entity\Project;
use BugBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IssueRepositoryTest extends KernelTestCase
{
    /** @var  EntityManager */
    private $em;
    /** @var  User */
    private $user;
    /** @var  Issue */
    private $issue;
    /** @var  Project */
    private static $project;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();
        /** @var Registry */
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        $em = $this->em;
        $this->user = $user = $em->getRepository('BugBundle:User')->findOneBy(array('username' => 'user'));

        self::$project = $project = new Project();
        $project->setSummary('asdfasdf')->setLabel('asdfasdf')->setCreator($user)->setCode('1WE');
        $issuePriority = $em->getRepository('BugBundle:IssuePriority')->findOneBy(array('label' => 'High'));
        $issueResolution = $em->getRepository('BugBundle:IssueResolution')->findOneBy(array('label' => 'Invalid'));
        $issueStatus = $em->getRepository('BugBundle:IssueStatus')->findOneBy(array('label' => IssueStatus::OPEN));
        $this->issue = $issue = new Issue();
        $issue->addCollaborator($user);
        $issue->setSummary('asdf')->setCode('123')->setAssignee($user)->setDescription('sdf')
            ->setProject($project)->setType(Issue::TYPE_BUG)->setPriority($issuePriority)
            ->setResolution($issueResolution)->setStatus($issueStatus)->setReporter($user);
        $em->persist($issue);
        $em->persist($project);
        $em->flush();


    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        parent::tearDown();
        $em = $this->em;
        $em->remove($em->find('BugBundle:Project', self::$project->getId()));
        $em->flush();
    }

    public function testRepo()
    {
        $em = $this->em;

        $query = $em->getRepository('BugBundle:Issue')->getActualIssuesByUserCollaboratorQuery($this->user);
        $this->assertInstanceOf('Doctrine\ORM\Query', $query);
        $result = $em->getRepository('BugBundle:Issue')->getActualIssuesByUserCollaborator($this->user);
        $this->assertNotCount(0, $result);
        $query = $em->getRepository('BugBundle:Issue')->getIssuesByUserQuery($this->user);
        $this->assertInstanceOf('Doctrine\ORM\Query', $query);

        $query = $em->getRepository('BugBundle:Issue')->getAllIssuesQuery();
        $this->assertInstanceOf('Doctrine\ORM\Query', $query);
        $this->assertNotCount(0, $query->getResult());

        $result = $em->getRepository('BugBundle:Issue')->checkIssueUserAccess($this->user, $this->issue);

    }
}
