<?php

namespace BugBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class BugTestCase extends WebTestCase
{

    use EntitySetHelper;

    /** @var Client $client */
    private static $client;

    /**
     * @param $login
     * @param $pass
     * @param $role
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    private function login($login, $pass, $role)
    {
        $client = static::createClient();
        $client->restart();
        $client->followRedirects();
        $crawler = $client->request('GET', $client->getContainer()->get('router')->generate('login_route'));
        $this->assertNotCount(0, $crawler->filter('html:contains("Login")'));
        $form = $crawler->selectButton('Sign In')->form();
        $form['_username'] = $login;
        $form['_password'] = $pass;
        $crawler = $client->submit($form);
        $this->assertTrue($crawler->filter('#user_auth:contains("'.$role.'")')->count() > 0);
        $this->setClient($client);

        return $client;
    }


    public static function getStaticClient()
    {
        if (null === self::$client) {
            self::$client = static::createClient();
        }

        return self::$client;
    }

    protected function getClient()
    {
        return self::getStaticClient();
    }

    protected function setClient(Client $client)
    {
        self::$client = $client;
    }

    protected function loginAsUserViaForm()
    {
        return $this->login('user', 'user', 'ROLE_USER');
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function loginAsAdminViaForm()
    {
        return $this->login('admin', 'admin', 'ROLE_ADMIN');
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function loginAsManagerViaForm()
    {
        return $this->login('manager', 'manager', 'ROLE_MANAGER');
    }

    /**
     * @param array $checkFields
     * @param Crawler $crawler
     * @return void
     */
    protected function checkAllFieldsValidationErrors(array $checkFields, Crawler $crawler)
    {
        foreach ($checkFields as $field) {
            $this->assertNotCount(0, $crawler->filter('#'.$field)->parents()->filter('ul'));
        }
    }

    protected function loginAsUser()
    {
        $client = static::createClient(
            array(),
            array(
                'PHP_AUTH_USER' => 'user',
                'PHP_AUTH_PW' => 'user',
            )
        );
        $client->followRedirects();
        $this->setClient($client);

        return $client;

    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function loginAsAdmin()
    {
        $client = static::createClient(
            array(),
            array(
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'admin',
            )
        );
        $client->followRedirects();
        $this->setClient($client);

        return $client;
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function loginAsManager()
    {
        $client = static::createClient(
            array(),
            array(
                'PHP_AUTH_USER' => 'manager',
                'PHP_AUTH_PW' => 'manager',
            )
        );
        $client->followRedirects();
        $this->setClient($client);

        return $client;
    }
}
