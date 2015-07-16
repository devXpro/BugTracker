<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 16.07.15
 * Time: 13:40
 */

namespace BugBundle\Tests\Unit\Services;


use BugBundle\Entity\User;
use BugBundle\Services\UserManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  EncoderFactoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $encoderFactory;

    /** @var  UserManager */
    protected $userManager;

    protected function setUp()
    {
        $this->encoderFactory = $this->getMock('Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface');
        $this->userManager = new UserManager($this->encoderFactory);
    }

    /**
     * @dataProvider testEncodePasswordDataProvider
     * @param User $user
     * @param User $resultUser
     * @throws \Exception
     */
    public function testEncodePassword(User $user, User $resultUser)
    {
        $encoder = $this->getMock('Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface');
        $encoder->expects($this->once())
            ->method('encodePassword')
            ->with(
                $this->isType('string'),
                $this->logicalOr($this->isType('string'), $this->isNull())
            )
            ->will(
                $this->returnCallback(
                    function ($pass) {
                        return md5($pass);
                    }
                )
            );
        $this->encoderFactory
            ->expects($this->once())
            ->method('getEncoder')
            ->with($this->isInstanceOf('BugBundle\Entity\User'))
            ->will($this->returnValue($encoder));
        $result = $this->userManager->encodePassword($user);
        $this->assertEquals($resultUser, $result);
    }

    public function testEncodePasswordDataProvider()
    {
        $user = new User();
        $user->setUsername('name');
        $user->setPassword('pass');

        $resultUser = clone $user;
        $resultUser->setPassword(md5($resultUser->getPassword()));

        return [
            [$user, $resultUser],
        ];
    }

    /**
     * @dataProvider testEncodePasswordExceptionsDataProvider
     * @param User $user
     * @param $exceptionMessage
     */
    public function testEncodePasswordExceptions(User $user, $exceptionMessage)
    {
        $this->setExpectedException('\Exception', $exceptionMessage);
        $this->userManager->encodePassword($user);

    }

    /**
     * @return array
     */
    public function testEncodePasswordExceptionsDataProvider()
    {
        return
        [
            [(new User())->setPassword('pass'), 'Username in not define'],
            [(new User())->setUsername('pass'), 'Password in not define'],

        ];

    }
}
