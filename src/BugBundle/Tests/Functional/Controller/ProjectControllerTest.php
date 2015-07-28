<?php

namespace BugBundle\Tests\Functional\Controller;

use BugBundle\Tests\BugTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

class ProjectControllerTest extends BugTestCase
{
    const PROJECT_LABEL = 'Real Test Project';

    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getManager();
        $projects = $em->getRepository('BugBundle:Project')->findBy(array('label' => self::PROJECT_LABEL));
        foreach ($projects as $project) {
            $em->remove($project);
        }
        $em->flush();
    }

    public function testCreateProjectByUser()
    {
        //User is can't create projects
        $client = $this->loginAsUser();
        $route = $client->getContainer()->get('router')->generate('bug_project_create');
        $crawler = $client->request('GET', $route);
        $this->assertNotCount(0, $crawler->filter('.error'));
    }


    public function testCreateProjectByManager()
    {
        //Manager can create Project
        $client = $this->loginAsManager();
        $route = $client->getContainer()->get('router')->generate('bug_project_create');
        $crawler = $client->request('GET', $route);
        $this->assertCount(0, $crawler->filter('.error'));
        $this->assertNotCount(0, $crawler->filter('label[for=bug_project_label]'));
        $this->makeProject($client, $crawler);
    }

    /** @depends testCreateProjectByManager */
    public function testDeleteProject()
    {
        $client = $this->loginAsManager();
        $route = $client->getContainer()->get('router')->generate('projects_list');
        $crawler = $client->request('GET', $route);
        $urls = $crawler->filterXPath("//a[contains(@href,'project/delete')]")->each(
            function (Crawler $node) {
                return $node->attr('href');
            }
        );

        $crawler = $client->request('GET', $urls[0]);
        $this->assertEquals('delete is not need', $crawler->filter('p')->html());
    }

    public function testCreateProjectByAdmin()
    {
        //Admin can create Project
        $client = $this->loginAsAdmin();
        $route = $client->getContainer()->get('router')->generate('bug_project_create');
        $crawler = $client->request('GET', $route);
        $this->assertCount(0, $crawler->filter('.error'));
        $this->assertNotCount(0, $crawler->filter('label[for=bug_project_label]'));
        $this->makeProject($client, $crawler);
    }

    /**
     * Test Edit created tasks, save and check
     * @depends testCreateProjectByUser
     */
    public function testEditByUser()
    {
        $client = $this->loginAsUser();
        $crawler = $this->getEditPage($client);
        $this->assertNotCount(0, $crawler->filter('.error'));
    }

    /**
     * Test Edit created tasks, save and check
     * @depends testCreateProjectByManager
     */
    public function testEditByManager()
    {
        $client = $this->loginAsManager();
        $crawler = $this->getEditPage($client);
        $this->assertCount(0, $crawler->filter('.error'));
        $this->makeProject($client, $crawler);
    }

    /**
     * Test Edit created tasks, save and check
     * @depends testCreateProjectByAdmin
     */
    public function testEditByAdmin()
    {
        $client = $this->loginAsAdmin();
        $crawler = $this->getEditPage($client);
        $this->assertCount(0, $crawler->filter('.error'));
        $this->makeProject($client, $crawler);
    }

    /**
     * @param Client $client
     * @return Crawler
     */
    private function getEditPage(Client $client)
    {
        $route = $client->getContainer()->get('router')->generate('projects_list');
        $crawler = $client->request('GET', $route);
        $urls = $crawler->filterXPath("//a[contains(@href,'project/edit')]")->each(
            function (Crawler $node) {
                return $node->attr('href');
            }
        );
        $this->assertNotCount(0, $urls);
        $url = $urls[rand(0, count($urls) - 1)];

        return $client->request('GET', $url);
    }

    /**
     * Save new Project, check validation rules
     * @param Client $client
     * @param Crawler $crawler
     */
    private function makeProject(Client $client, Crawler $crawler)
    {
        $form = $crawler->selectButton('Save')->form();
        $memberIds = $crawler->filter('#bug_project_members > option')->each(
            function (Crawler $node) {
                return $node->attr('value');

            }
        );
        $form['bug_project[label]'] = '';
        $form['bug_project[summary]'] = '';
        $form['bug_project[code]'] = 'Biggie';
        $form['bug_project[members]']->setValue($memberIds);
        $crawler = $client->submit($form);
        $checkFields = array('bug_project_label', 'bug_project_summary', 'bug_project_code');
        $this->checkAllFieldsValidationErrors($checkFields, $crawler);
        $form['bug_project[label]'] = self::PROJECT_LABEL;
        $form['bug_project[summary]'] = 'Without Mistakes. This summary contain full data';
        $form['bug_project[code]'] = 'XXX';
        $crawler = $client->submit($form);
        $this->assertNotCount(0, $crawler->filter('.project_view'));
    }
}
