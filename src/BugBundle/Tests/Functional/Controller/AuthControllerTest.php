<?php

namespace BugBundle\Tests\Functional\Controller;

use BugBundle\Tests\BugTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * @SuppressWarnings(PHPMD.ElseExpression)
 * Class AuthControllerTest
 * @package BugBundle\Tests\Functional\Controller
 */
class AuthControllerTest extends BugTestCase
{
    /**
     * {@inheritdoc}
     */
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getManager();
        foreach (self::registrationProvider() as $data) {
            if ($data['result']) {
                $user = $em->getRepository('BugBundle:User')->findOneBy(array('username' => $data['name']));
                if ($user) {
                    $em->remove($user);
                }
            }
        }
        $em->flush();
    }


    public function testAuthAdmin()
    {
        $this->loginAsAdminViaForm();
    }

    public function testManagerAdmin()
    {
        $this->loginAsManagerViaForm();
    }

    public function testUserAdmin()
    {
        $this->loginAsUserViaForm();
    }


    /**
     *
     * @dataProvider registrationProvider
     * @param $username
     * @param $email
     * @param $password
     * @param $result
     */
    public function testRegisterUser($username, $email, $password, $result)
    {
        /** @var Client $client */
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', $client->getContainer()->get('router')->generate('registration_route'));
        $form = $crawler->selectButton('Register')->form();
        $form['form[email]'] = $email;
        $form['form[username]'] = $username;
        $form['form[password]'] = $password;
        $crawler = $client->submit($form);
        if (!$result) {
            $fields = array('form_email', 'form_username', 'form_password');
            $this->checkAllFieldsValidationErrors($fields, $crawler);
        } else {
            $this->assertNotCount(0, $crawler->filter('.bug_login_form'));
        }


    }

    public function registrationProvider()
    {
        return [
            ['name' => 'admin', 'email' => 'admin@ukr.net', 'password' => '123', 'result' => false],
            ['name' => 'user', 'email' => 'user.ukr.net', 'password' => '123', 'result' => false],
            [
                'name' => 'testUser',
                'email' => 'test_user@ukr.net',
                'password' => 'superDuperPassword',
                'result' => true,
            ],
        ];
    }
}
