<?php
/**
 * Created by PhpStorm.
 * User: roma
 * Date: 30.06.15
 * Time: 12:47
 */

namespace BugBundle\Services;


use BugBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;


class UserManager
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param User $user
     * @param $password
     * @return User
     * @throws \Exception
     */
    public function encodePassword(User $user)
    {
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->getUsername();
        if (!$user->getUsername())
            throw new \Exception('Username in not define');
        if (!$user->getPassword())
            throw new \Exception('Password in not define');
        $pass = $encoder->encodePassword($user->getPassword(), $user->getSalt());
        $user->setPassword($pass);
        return $user;
    }

}