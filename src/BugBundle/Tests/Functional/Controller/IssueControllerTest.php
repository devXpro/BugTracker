<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 20.07.15
 * Time: 15:38
 */

namespace BugBundle\Tests\Functional\Controller;


use BugBundle\Entity\Project;
use BugBundle\Entity\Role;
use BugBundle\Entity\User;
use BugBundle\Tests\BugTestCase;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

class IssueControllerTest extends BugTestCase
{

    const PROJECT_LABEL = 'My project For test!';
    /** @var  EntityManager $em */
    private static $em;
    private static $user;
    private static $newUser;
    private static $manager;
    private static $admin;
    private static $adminProject;
    /** @var Project $managerProject */
    private static $managerProject;

    private static $userIssueId;
    private static $adminIssueId;

    public static function setUpBeforeClass()
    {
        $client = static::createClient();
        /** @var Registry em */
        $em = $client->getContainer()->get('doctrine')->getManager();
        self::$manager = $em->getRepository('BugBundle:User')->findOneBy(array('username' => 'manager'));
        self::$admin = $em->getRepository('BugBundle:User')->findOneBy(array('username' => 'admin'));
        self::$user = $em->getRepository('BugBundle:User')->findOneBy(array('username' => 'user'));

        $adminProject = self::$adminProject = self::getEntity(
            'BugBundle\Entity\Project',
            array('summary', 'code'),
            true
        );

        $managerProject = self::$managerProject = clone $adminProject;
        $adminProject->setLabel(self::PROJECT_LABEL.' admin');
        $managerProject->setLabel(self::PROJECT_LABEL.' manager');
        $adminProject->setCreator(self::$admin);
        $managerProject->setCreator(self::$admin);
        $managerProject->addMember(self::$user);
        $em->persist($adminProject);
        $em->persist($managerProject);
        $newUser = self::$newUser = new User();
        $roleUser = $em->getRepository('BugBundle:Role')->findOneBy(array('role' => Role::ROLE_USER));
        $newUser->addRole($roleUser)->setUsername('anotherUser')->setPassword('anotherUser')->setEmail('12312222');
        $client->getContainer()->get('bug.userManager')->encodePassword($newUser);
        $em->persist($newUser);
        $em->flush();
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getManager();
        $em->remove($em->find('BugBundle:Project', self::$managerProject->getId()));
        $em->remove($em->find('BugBundle:Project', self::$adminProject->getId()));
        $em->remove($em->find('BugBundle:User', self::$newUser->getId()));
        $em->flush();
    }

    public function testNewUserCreateIssueNoProjectsMessage()
    {
        $client = static::createClient(
            array(),
            array(
                'PHP_AUTH_USER' => 'anotherUser',
                'PHP_AUTH_PW' => 'anotherUser',
            )
        );
        $client->followRedirects();
        $route = $client->getContainer()->get('router')->generate('bug_issue_create');
        $crawler = $client->request('GET', $route);
        $this->assertNotCount(0, $crawler->filter('.error'));
        $this->assertCount(0, $crawler->filter('#issue_edit_form'));
    }


    public function testCreateIssueByUser()
    {
        $client = $this->loginAsUser();
        $route = $client->getContainer()->get('router')->generate('bug_issue_create');
        $crawler = $client->request('GET', $route);
        $this->assertCount(0, $crawler->filter('.error'));
        $this->assertNotCount(0, $crawler->filter('#issue_edit_form'));
        $this->makeIssue($crawler, $client, self::$managerProject, 'user');

    }


    public function testCreateIssueByAdmin()
    {
        $client = $this->loginAsUser();
        $route = $client->getContainer()->get('router')->generate('bug_issue_create');
        $crawler = $client->request('GET', $route);
        $this->assertCount(0, $crawler->filter('.error'));
        $this->assertNotCount(0, $crawler->filter('#issue_edit_form'));
        $this->makeIssue($crawler, $client, self::$managerProject, 'admin');

    }

    /**
     * @depends testCreateIssueByUser
     */
    public function testEditIssueByUser()
    {
        $client = $this->loginAsUser();
        $route = $client->getContainer()->get('router')->generate(
            'bug_issue_edit',
            array('issue' => self::$userIssueId)
        );
        $crawler = $client->request('GET', $route);
        $this->makeIssue($crawler, $client, self::$managerProject, 'user');
    }

    /**
     * @depends testCreateIssueByAdmin
     */
    public function testEditIssueByAdmin()
    {
        $client = $this->loginAsUser();
        $route = $client->getContainer()->get('router')->generate(
            'bug_issue_edit',
            array('issue' => self::$adminIssueId)
        );
        $crawler = $client->request('GET', $route);
        $this->makeIssue($crawler, $client, self::$managerProject, 'admin');
    }

    /**  @depends testEditIssueByAdmin */
    public function testIssueComment()
    {
        $client = $this->loginAsUser();
        $route = $client->getContainer()->get('router')->generate(
            'issue_comment',
            array('issue' => self::$adminIssueId)
        );
        $crawler = $client->request('GET', $route);
        $this->assertNotCount(0, $crawler->filter('form[name="bug_issue_comment"]'));
        $form = $crawler->filter('button[type="submit"]')->form();
        $comment = 'bla bla comment';
        $form['bug_issue_comment[body]'] = $comment;
        $crawler = $client->submit($form);
        $this->assertContains($comment, $crawler->html());

    }

    /** @depends testIssueComment */
    public function testDeleteByAdmin()
    {
        $client = $this->loginAsAdmin();
        $route = $client->getContainer()->get('router')->generate(
            'bug_issue_delete',
            array('issue' => self::$adminIssueId)
        );
        $crawler = $client->request('GET', $route);
        $this->assertNotCount(0, $crawler->filter('#issues_list'));

    }


    /** @depends  testCreateIssueByUser */
    public function testIssueListByUser()
    {
        $client = $this->loginAsUser();
        $route = $client->getContainer()->get('router')->generate('issues_list');
        $crawler = $client->request('GET', $route);
        $this->assertNotCount(0, $crawler->filter('#issues_list'));
    }

    private function  makeIssue(Crawler $crawler, Client $client, Project $project, $username)
    {
        $form = $crawler->filter('button[type="submit"]')->form();
        $getValue = function (Crawler $node) {
            return $node->attr('value');
        };
        $issueTypeIds = $crawler->filter('#bug_issue_type > option')->each($getValue);
        $issuePriorityIds = $crawler->filter('#bug_issue_priority > option')->each($getValue);
        $issueStatusIds = $crawler->filter('#bug_issue_status > option')->each($getValue);
        $issueResolutionIds = $crawler->filter('#bug_issue_resolution > option')->each($getValue);
        $issueAssigneeIds = $crawler->filter('#bug_issue_assignee > option')->each($getValue);
        $form['bug_issue[project]'] = $project->getId();
        $form['bug_issue[summary]'] = '1';
        $form['bug_issue[code]'] = '1';
        $form['bug_issue[description]'] = '1';
        $form['bug_issue[type]'] = $issueTypeIds[0];
        $form['bug_issue[priority]'] = $issuePriorityIds[0];
        $form['bug_issue[status]'] = $issueStatusIds[0];
        $form['bug_issue[resolution]'] = $issueResolutionIds[0];
        $form['bug_issue[assignee]'] = $issueAssigneeIds[0];
        $crawler = $client->submit($form);
        $this->checkAllFieldsValidationErrors(
            array('bug_issue_description', 'bug_issue_summary', 'bug_issue_code'),
            $crawler
        );

        $form['bug_issue[summary]'] = 'It is good summary';
        $form['bug_issue[code]'] = 'PRG';
        $form['bug_issue[description]'] = 'Is is well description, length 10 chars and more';
        $crawler = $client->submit($form);
        $this->assertNotCount(0, $crawler->filter('#issue_view'));
        $linkProperty = $username.'IssueId';
        $links = $crawler->filterXPath("//a[contains(@href,'issue/edit')]")->each(
            function (Crawler $node) {
                return $node->attr('href');
            }
        );
        self::$$linkProperty = substr($links[0], strrpos($links[0], '/') + 1);

    }


}
