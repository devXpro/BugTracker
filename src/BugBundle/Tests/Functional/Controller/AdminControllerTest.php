<?php

namespace BugBundle\Tests\Functional\Controller;

use BugBundle\Entity\Role;
use BugBundle\Entity\User;
use BugBundle\Tests\BugTestCase;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

class AdminControllerTest extends BugTestCase
{
    /** @var  User $user */
    private static $user;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        $client = static::createClient();
        /** @var Registry em */
        $em = $client->getContainer()->get('doctrine')->getManager();

        $roleUser = $em->getRepository('BugBundle:Role')->findOneBy(array('role' => Role::ROLE_USER));
        self::$user = new User();
        self::$user->addRole($roleUser)->setUsername('anotherUser')->setPassword('anotherUser')->setEmail('12312222');
        $client->getContainer()->get('bug.userManager')->encodePassword(self::$user);
        $em->persist(self::$user);
        $em->flush();
    }

    public function testUsersListAction()
    {
        /** @var Client $client */
        $client = $this->loginAsAdmin();
        $route = $client->getContainer()->get('router')->generate('admin_users_list');
        $crawler = $client->request('GET', $route);
        $this->assertNotCount(0, $crawler->filter('#admin_users_list'));
    }

    public function testEditUser()
    {
        $client = $this->loginAsAdmin();
        $route = $client->getContainer()->get('router')->generate(
            'bug_admin_useredit',
            array('user' => self::$user->getId())
        );
        $crawler = $client->request('GET', $route);
        $this->assertNotCount(0, $crawler->filter('#user_edit'));
        $form = $crawler->filter('button[type="submit"]')->form();
        $form['bug_user[email]'] = 'New@Email.ru';
        $form['bug_user[username]'] = 'New Username';
        $form['bug_user[plainPassword][first]'] = 'New pass';
        $form['bug_user[plainPassword][second]'] = 'New pass';

        $roleIds = $crawler->filter('#bug_user_roles > option')->each(
            function (Crawler $node) {
                return $node->attr('value');
            }
        );

        $form['bug_user[roles]']->setValue($roleIds);
        $crawler = $client->submit($form);
        $this->assertNotCount(0, $crawler->filter('#admin_users_list'));
    }

    /** @depends testEditUser */
    public function testUserDelete()
    {
        $client = $this->loginAsAdmin();
        $route = $client->getContainer()->get('router')->generate(
            'bug_admin_userdelete',
            array('user' => self::$user->getId())
        );
        $crawler = $client->request('GET', $route);

        $this->assertNotCount(0, $crawler->filter('#admin_users_list'));
    }
}
