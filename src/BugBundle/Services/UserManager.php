<?php

namespace BugBundle\Services;

use BugBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserManager
{
    /**
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param User $user
     * @return User
     * @throws \Exception
     */
    public function encodePassword(User $user, $newPassword = null)
    {
        $user->getUsername();
        if (!$user->getUsername()) {
            throw new UsernameNotFoundException('Username in not define');
        }
        if (!$user->getPassword()) {
            throw new \Exception('Password in not define');
        }
        $pass = $newPassword ? $newPassword : $user->getPassword();
        $encoder = $this->encoderFactory->getEncoder($user);
        $pass = $encoder->encodePassword($pass, $user->getSalt());
        $user->setPassword($pass);

        return $user;
    }
}
