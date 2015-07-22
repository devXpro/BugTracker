<?php

namespace BugBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', $client->getContainer()->get('router')->generate('login_route'));
        $h=$crawler->html();
        $this->assertTrue($crawler->filter('html:contains("Login")')->count() > 0);
    }
}
