<?php
use BugBundle\Entity\Activity;
use BugBundle\Entity\Issue;
use BugBundle\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Created by PhpStorm.
 * User: roma
 * Date: 21.07.15
 * Time: 19:56
 */
class ActivityRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    private $user;

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

        $query = $em->getRepository('BugBundle:Activity')->getActivitiesByUserQuery($this->user);
        $this->assertInstanceOf('Doctrine\ORM\Query', $query);
        $result = $em->getRepository('BugBundle:Activity')->getActivitiesByUser($this->user);
        $this->assertNotCount(0, $result);
    }


}
