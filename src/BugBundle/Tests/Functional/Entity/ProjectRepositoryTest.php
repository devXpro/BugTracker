<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 21.07.15
 * Time: 20:28
 */

namespace BugBundle\Tests\Functional\Entity;


use BugBundle\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProjectRepositoryTest extends KernelTestCase
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

        self::$project = $project = new Project();
        $project->setSummary('asdfasdf')->setLabel('asdfasdf')->setCreator($user)->setCode('1WE');
        $project->addMember($user);
        $em->persist($project);

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
        $projectRepo = $em->getRepository('BugBundle:Project');
        $query = $projectRepo->getAllProjectsQuery();
        $this->assertInstanceOf('Doctrine\ORM\Query', $query);
        $query = $projectRepo->getProjectsByUserQuery($this->user);
        $this->assertInstanceOf('Doctrine\ORM\Query', $query);

        $result = $projectRepo->getProjectsByUser($this->user);
        $this->assertNotCount(0, $result);

        $result = $projectRepo->countProjectsByUser($this->user);
        $this->assertNotEquals(0, $result);

        $result = $projectRepo->checkAccessProject($this->user, self::$project);
        $this->assertNotEquals(0, $result);

    }
}
