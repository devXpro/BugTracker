<?php

namespace BugBundle\Tests\Functional\Controller;

use BugBundle\Tests\BugTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class UserControllerTest extends BugTestCase
{
    /**
     * @dataProvider profileProvider
     * @param $fullName
     * @param $email
     * @param $password
     * @param $result
     */
    public function testProfileEditAction($fullName, $email, $password, $result)
    {
        /** @var Client $client */
        $client = $this->loginAsUser();
        $route = $client->getContainer()->get('router')->generate('user_profile_edit');
        $crawler = $client->request('GET', $route);
        $form = $crawler->filter('button[type=submit]')->form();
        $form['bug_user_profile[email]'] = $email;
        $form['bug_user_profile[fullName]'] = $fullName;
        $form['bug_user_profile[password]'] = $password;
        $crawler = $client->submit($form);
        if (!$result) {
            $checkFields = array('bug_user_profile_email', 'bug_user_profile_password');
            $this->checkAllFieldsValidationErrors($checkFields, $crawler);
            $this->assertCount(0, $crawler->filter('#bug_user_page'));

        } else {
            $this->assertNotCount(0, $crawler->filter('#bug_user_page'));
        }
    }

    /**
     * @return array
     */
    public function profileProvider()
    {
        return [
            ['name' => 'admin', 'email' => 'admin@ukr.net', 'password' => '123', 'result' => false],
            ['name' => 'user', 'email' => 'user.ukr.net', 'password' => '123', 'result' => false],
            ['name' => 'testUser', 'email' => 'user@ukr.net', 'password' => 'user', 'result' => true],
        ];
    }
}
